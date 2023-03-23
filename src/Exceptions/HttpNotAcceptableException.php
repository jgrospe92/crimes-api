<?php
namespace Vanier\Api\exceptions;
// declare(strict_types=1);
use Slim\Exception\HttpSpecializedException;

/**
 * Summary of HttpNotAcceptableException
 */
class HttpNotAcceptableException extends HttpSpecializedException
{

 
    /**
     * Summary of code
     * @var int
     */
    protected $code = 406;
    /**
     * Summary of message
     * @var string
     */
    protected $message = 'Requested resource does not match the provided criteria';
    /**
     * Summary of title
     * @var string
     */
    protected $title = '406 Not Acceptable';
    /**
     * Summary of description
     * @var string
     */
    protected $description = 'Requested resource does not match the provided criteria';
    
}
