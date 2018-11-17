<?PHP

/**
 * hiAPI NicRu plugin
 *
 * @link      https://github.com/hiqdev/hiapi-nicru
 * @package   hiapi-nicru
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiapi\nicru;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use hiapi\nicru\requests\AbstractRequest;
use hiapi\nicru\parsers\NicRuResponseParser;

class HttpClient
{
    /* @var object [[Client]] */
    protected $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Perform http request
     * @param string $httpMethod
     * @param object [[AbstractRequest]] $request
     * @return array
     */
    public function performRequest (string  $httpMethod, AbstractRequest $request) : array
    {
        $guzzleResponse = $this->request($httpMethod, $request);
        $response = $this->parseGuzzleResponse($guzzleResponse, $request);
        return $response;
    }

    /**
     * @param array $data
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function fetchGet (AbstractRequest $request): Response
    {
        $query = '?' . $this->prepareQuery($reuqest);
        return $this->client->request('GET', $query);
    }

    /**
     * @param array|null $data
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function fetchPost (AbstractRequest $request): Response
    {
        $query = $this->prepareQuery($request);
        return $this->client->request('POST', '', [
            'body' => $query,
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
        ]);
    }

    /**
     * @param array $data
     * @return string
     */
    private function prepareQuery(AbstractRequest $request): string
    {
        return "SimpleRequest=" . urlencode(sprintf("%s", $request));
    }

    /**
     * @param string $httpMethod
     * @param object [[AbstractRequest]] $request
     * @return array|null
     */
    private function request (string $httpMethod, AbstractRequest $request): ?Response
    {
        if (!strcasecmp($httpMethod, 'GET')) {
            return $this->fetchGet($request);
        }

        if (!strcasecmp($httpMethod, 'POST')) {
            return $this->fetchPost($request);
        }
        return null;
    }

    /**
     * @param Response $guzzleResponse
     * @return array|int
     * @throws \hiapi\nicru\exceptions\NicRuException
     */
    private function parseGuzzleResponse(Response $guzzleResponse, AbstractRequest $request)
    {
        if ($guzzleResponse->getStatusCode() !== 200) {
            throw new \Exception(trim($guzzleResponse->getReasonPhrase()));
        }

        $response = trim(mb_convert_encoding($guzzleResponse->getBody()->getContents(), 'UTF-8', 'KOI8-R'));
        return NicRuResponseParser::parse($response, $request);
    }
}
