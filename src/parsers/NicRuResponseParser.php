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
     * @throws \hiapi\nicru\exceptions\NicRuException
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

        $blocks = self::explodeToBlocks($response);
        if ($parseRules['answer']['skipfullparse'] === true) {
            if (empty($parseRules['answer']['fields'])) {
                return [
                    'success' => true,
                ];
            }

            return self::getBlockData($blocks['header'], $parseRules['answer']['fields']);
        }
        if (empty($blocks[$parseRules['answer']['delimiter']])) {
            return [];
        }

        foreach ($blocks[$parseRules['answer']['delimiter']] as $block) {
            $result[] = self::getBlockData($block, $parseRules['answer']['fields']);
        }

        if (empty($parseRules['search'])) {
            return reset($result);
        }

        $searchData = self::getSearchData($blocks[$parseRules['search']]);
        if ($searchData['limit'] === 1) {
            return reset($result);
        }

        return $result;
    }

    /**
     * Explode response to blocks
     *
     * @param string $response
     * @return array of arrays
     */
    private static function explodeToBlocks(string $response) : array
    {
        if (mb_strpos($response, "[") === false) {
            return [
                'header' => explode("\n", trim($response)),
            ];
        }
        $header = trim(mb_substr($response, 0, mb_strpos($response, "[")));
        $blocks['header'] = explode("\n", $header);
        $response = mb_substr($response, mb_strpos($response, "["));
        $blockName = null;
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

        return $blocks;
    }

    /**
     * @param array $block
     * @param array $fields
     * @return array
     */
    private static function getBlockData(array $block, array $fields) : array
    {
        foreach ($block as $line) {
            [$field, $value] = explode(":", $line, 2);
            if (isset($fields[$field])) {
                $result = self::setParsedValue($fields[$field], $value, $result);
            }
        }

        return $result;
    }

    /**
     * @param string $field
     * @param string $value
     * @param array|null $res
     * @return array
     */
    private static function setParsedValue(string $field, string $value, $res) : array
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
    private static function getSearchData($block) : array
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
