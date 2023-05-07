<?php

namespace Vanier\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Vanier\Api\Helpers\Validator;

class AboutController extends BaseController
{
    public function handleAboutApi(Request $request, Response $response, array $uri_args)
    {
        $data = array(
            'about' => 'Welcome, this is a Web services that provides fictional crimes information all around the world',
            'version' => 'v1',
            'resources' => ['/cases', '/offenders','/victims','/defendants','/prosecutors','/investigators','/courts','/verdicts','/judges'],
            'Developers' => ['Jeffrey','Saqliyan','Theodore', 'Alex']
        );                
        return $this->prepareOkResponse($response, $data);
    }
}
