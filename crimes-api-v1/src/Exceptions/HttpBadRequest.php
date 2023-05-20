<?php
namespace Vanier\Api\exceptions;
// declare(strict_types=1);
use Slim\Exception\HttpSpecializedException;

/**
 * Summary of HttpBadRequest
 */
class HttpBadRequest extends HttpSpecializedException
{

 
    /**
     * Summary of code
     * @var int
     */
    protected $code = 400;
    /**
     * Summary of message
     * @var string
     */
    protected $message = 'Invalid request syntax';
    /**
     * Summary of title
     * @var string
     */
    protected $title = '400 BAD REQUEST';
    /**
     * Summary of description
     * @var string
     */
    protected $description = 'Invalid request syntax';
    
    
}
