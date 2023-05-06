<?php
namespace Vanier\Api\Helpers;
use Exception;
use GuzzleHttp\Client;

class WebServiceInvoker
{
    private $request_options = [];

    public function __construct(array $options = [])
    {
        $this->request_options = $options;
    }

    public function invokeUri(string $base_uri, string $resource_name)
    {

        // TODO: Implement your HTTP client here. 
        // Create a client with a base URI
        $client = new Client(['base_uri' => $base_uri]);

        // 1. process the request
        //$response = $client->request('GET', '/shows', $this->request_options);
        $response = $client->request('GET', $resource_name, $this->request_options);

        // 2. Process the response

        $code = $response->getStatusCode(); // 200
        $reason = $response->getReasonPhrase(); // OK
        $content_type = $response->getHeaderLine('Content-type');

        // check the status code
        if ($code !== 200) {
            throw new Exception('Something went wrong.' . $reason);
        }
        // validate content type
        if (!str_contains($content_type, 'application/json')) {
            throw new Exception('Unprocessable data format.' . $reason);
        }

        // 3. process the body
        $data = json_decode($response->getBody()->getContents());
        $res['status'] = $code;
        $res['reason'] = $reason;
        $res['data'] = $data;

        return $res['data'];
    }
}