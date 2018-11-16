<?PHP

namespace hiapi\nicru;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use hiapi\nicru\requests\AbstractRequest;


class HttpClient
{
    protected $client;

    protected $successCode = 200;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function performRequest (string  $httpMethod, AbstractRequest $request)
    {
        $guzzleResponse = $this->request($httpMethod, $request);
        $response = $this->parseGuzzleResponse($guzzleResponse, $request);
        return $response;
    }

    /**
     * @param string $command
     * @param array $data
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function fetchGet (string $command, AbstractRequest $request): Response
    {
        $query = $command . '?' . $this->prepareQuery($reuqest);
        return $this->client->request('GET', $query);
    }

    /**
     * @param string $command
     * @param array|null $data
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function fetchPost (string $command, AbstractRequest $request): Response
    {
        $query = $this->prepareQuery($request);
        $res = $this->client->request('POST',  $command, [
            'body' => $query,
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
        ]);
        return $res;
    }

    /**
     * @param array $data
     * @return string
     */
    private function prepareQuery(AbstractRequest $request): string
    {
        return "SimpleRequest=" . urlencode(sprintf("%s", $request));
    }

    private function request (string $httpMethod, AbstractRequest $request): ?Response
    {
        if (!strcasecmp($httpMethod, 'GET')) {
            return $this->fetchGet('', $request);
        }
        else if (!strcasecmp($httpMethod, 'POST')) {
            return $this->fetchPost('', $request);
        }
        return null;
    }

    /**
     * @param $guzzleResponse
     * @return array|int
     */
    private function parseGuzzleResponse($guzzleResponse, AbstractRequest $request)
    {
        if ($guzzleResponse->getStatusCode() !== 200) {
            throw new \Exception(trim($guzzleResponse->getReasonPhrase()));
        }

        $response = mb_convert_encoding($guzzleResponse->getBody()->getContents(), 'UTF-8', 'KOI8-R');
        $lines = explode("\n", $response);
        if (!preg_match('#State: 200#', $lines[0])) {
            throw new \Exception(trim(preg_replace('#State: [0-9]+#', '', $lines[0])));
        }

        $answerParams = $request->getAnswerParams();
        if (empty($request->getAnswerParams())) {
            return [
                'status' => $guzzleResponse->getReasonPhrase(),
            ];
        }

        $blocks = explode("[{$answerParams['delimiter']}]", $response);
        $header = array_shift($blocks);
        if (empty($blocks)) {
            return [
                'status' => $guzzleResponse->getReasonPhrase(),
            ];
        }

        $i = 0;
        foreach ($blocks as $data) {
            $blockData = explode("\n", $data);
            $result[$i] = null;
            foreach ($blockData as $line) {
                [$field, $value] = explode(":", $line, 2);
                if (!empty($answerParams['fields'][$field])) {
                    $result[$i] = $this->setParsedValue($answerParams['fields'][$field], $value, $result[$i]);
                }
            }
            $i++;
        }

        return array_filter($result);
    }

    private function setParsedValue($field, $value, $res)
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

}
