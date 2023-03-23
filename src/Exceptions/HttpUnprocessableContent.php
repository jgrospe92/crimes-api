<?php
namespace Vanier\Api\exceptions;
// declare(strict_types=1);
use Slim\Exception\HttpSpecializedException;

/**
 * Summary of HttpUnprocessableContent
 */
class HttpUnprocessableContent extends HttpSpecializedException
{
    /**
     * Summary of code
     * @var int
     */
    protected $code = 422;
    /**
     * Summary of message
     * @var string
     */
    protected $message = 'Incorrect query parameter';
    /**
     * Summary of title
     * @var string
     */
    protected $title = '422 Unprocessable Content';
    /**
     * Summary of description
     * @var string
     */
    protected $description = 'The requested content is not supported';

    
    
}
