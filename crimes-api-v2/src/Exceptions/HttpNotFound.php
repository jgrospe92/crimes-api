<?php
namespace Vanier\Api\exceptions;
// declare(strict_types=1);
use Slim\Exception\HttpSpecializedException;

/**
 * Summary of HttpNotFound
 */
class HttpNotFound extends HttpSpecializedException
{

 
    /**
     * Summary of code
     * @var int
     */
    protected $code = 404;
    /**
     * Summary of message
     * @var string
     */
    protected $message = 'please check your query parameter or consult the documentations';
    /**
     * Summary of title
     * @var string
     */
    protected $title = '404 Not Found';
    /**
     * Summary of description
     * @var string
     */
    protected $description = 'The requested resource could not be found. Please verify the URI and try again.';
    
    
}
