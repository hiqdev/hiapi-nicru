<?php

/**
 * hiAPI NicRu plugin
 *
 * @link      https://github.com/hiqdev/hiapi-nicru
 * @package   hiapi-nicru
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiapi\nicru\parsers;

use hiapi\nicru\requests\AbstractRequest;
use hiapi\nicru\exceptions\ParserErrorException;

/**
 * Parse response.
 *
 * @author Yurii Myronchuk <bladeroot@gmail.com>
 */
class NicRuResponseParser
{
    const STATE_OK = 200;

    /**
     * @var string $response
     * @var object [[AbstractRequest]] $request
     * @return array
     * @throws NicRuExceptions
     */
    public static function parse(string $response, AbstractRequest $request) : array
    {
        if (!preg_match('#State: ' . self::STATE_OK . '#mui', $response)) {
            return NicRuErrorParser::parse($response, $request);
        }

        $parseRules = $request->getParseRules();
        if (empty($parseRules)) {
            throw new ParserErrorException("no parser rules provided");
        }

        $response = mb_substr($response, mb_strpos($response, "["));
        $blockName = null;
        $block = [];
        $i = 0;
        foreach (explode("\n", $response) as $line) {
            if (empty($line)) {
                continue;
            }

            if (preg_match('#\[(.+)\]#ui', $line)) {
                preg_match_all('#\[(.+)\]#ui', $line, $matches);
                $blockName = $matches[1][0];
                $i++;
                continue;
            }

            $blocks[$blockName][$i][] = $line;
        }

        if (empty($blocks[$parseRules['answer']['delimiter']])) {
            return [];
        }

        $i = 0;
        foreach ($blocks[$parseRules['answer']['delimiter']] as $block) {
            foreach ($block as $line) {
                [$field, $value] = explode(":", $line, 2);
                if (isset($parseRules['answer']['fields'][$field])) {
                    $result[$i] = self::setParsedValue($parseRules['answer']['fields'][$field], $value, $result[$i]);
                }
            }
            $i++;
        }

        if (empty($parseRules['search'])) {
            return reset($result);
        }

        $searchData = self::setSearchData($blocks[$parseRules['search']]);
        if ($searchData['limit'] === 1) {
            return reset($result);
        }

        return $result;
    }

    /**
     * @param string $field
     * @param string $value
     * @param array|null $res
     * @return array
     */
    private static function setParsedValue($field, $value, $res) : array
    {
        if (empty($res[$field])) {
            $res[$field] = $value;
            return $res;
        }

        if (is_array($res[$field])) {
            $res[$field][] = $value;
            return $res;
        }

        $tmp = $res[$field];
        $res[$field] = [];
        $res[$field][] = $tmp;
        $res[$field][] = $value;
        return $res;
    }

    /**
     * @param array $block
     * @return array
     */
    private static function setSearchData($block) : array
    {
        $block = reset($block);
        foreach ($block as $line) {
            [$field, $data] = explode(":", $line);
            $field = explode("-", $field);
            $result[end($field)] = (int) trim($data);
        }

        return $result;
    }
}
