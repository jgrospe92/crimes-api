<?php
namespace Vanier\Api\exceptions;
// declare(strict_types=1);
use Slim\Exception\HttpSpecializedException;

/**
 * Summary of HttpConflict
 */
class HttpConflict extends HttpSpecializedException
{

 
    /**
     * Summary of code
     * @var int
     */
    protected $code = 409;
    /**
     * Summary of message
     * @var string
     */
    protected $message = 'This request is poorly structured, please check the documentations';
    /**
     * Summary of title
     * @var string
     */
    protected $title = '409 Conflict';
    /**
     * Summary of description
     * @var string
     */
    protected $description = 'Please provide all the required fields';
    
    
}
