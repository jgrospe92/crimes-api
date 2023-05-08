<?php

namespace Vanier\Api\Helpers;

use DateTimeZone;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\WebProcessor;

class AppLoggingHelper
{
    public const LOG_CHANNEL_ACCESS = 'ACCESS';
    public const LOG_CHANNEL_CREATE = 'CREATE';
    public const LOG_CHANNEL_UPDATE = 'UPDATE';
    public const LOG_CHANNEL_DELETE = 'DELETE';
    public const LOG_CHANNEL_ERRORS = 'ERRORS';
    private  $logger = null;

    private array $options = [];
    public function __construct(array $in_options)
    {
        $this->options = $in_options;
        $this->initLoggers();
    }

    public function initLoggers()
    {
        // the default date format is "Y-m-d\TH:i:sP"
        $dateFormat = "Y-n-j, g:i:s a"; // I guess g for hours, i for minutes, and s for seconds.
        // the default output format is "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n"
        // we now change the default output format according to our needs.
        $output = "[%datetime%] %channel%.%level_name% > msg: %message% context: %context% data: %extra%\n";
        // finally, create a formatter
        $formatter = new LineFormatter($output, $dateFormat);
        //-- 1) A new log channel for general message.    
        $this->logger = new Logger($this->options['channel_name']);
        $this->logger->setTimezone(new DateTimeZone('America/Toronto'));
        $log_handler = new StreamHandler($this->options['file_path'], $this->options['log_level']);
        $log_handler->setFormatter($formatter);
        $log_handler->pushProcessor(new WebProcessor());
        $this->logger->pushHandler($log_handler);
        return $this->logger;
    }
    public function getAppLogger()
    {
        return $this->logger;
    }
    public static function getAccessLogger()
    {
        $helper = new AppLoggingHelper([
            'file_path'=>  APP_LOG_FILE_ACCESS,
            'channel_name' => self::LOG_CHANNEL_ACCESS,
            'log_level' =>Logger::INFO
         ]);
        $logger =  $helper->getAppLogger();
        return $logger;
    }
    public static function getCreateLogger()
    {
        $helper = new AppLoggingHelper([
            'file_path'=>  APP_LOG_FILE_POSTS,
            'channel_name' => self::LOG_CHANNEL_CREATE,
            'log_level' =>Logger::NOTICE
         ]);
        $logger =  $helper->getAppLogger();
        return $logger;
    }
    public static function getUpdateLogger()
    {
        $helper = new AppLoggingHelper([
            'file_path'=>  APP_LOG_FILE_UPDATES,
            'channel_name' => self::LOG_CHANNEL_UPDATE,
            'log_level' =>Logger::NOTICE
         ]);
        $logger =  $helper->getAppLogger();
        return $logger;
    }
    public static function getDeleteLogger()
    {
        $helper = new AppLoggingHelper([
            'file_path'=>  APP_LOG_FILE_DELETES,
            'channel_name' => self::LOG_CHANNEL_DELETE,
            'log_level' =>Logger::WARNING
         ]);
        $logger =  $helper->getAppLogger();
        return $logger;
    }
    public static function getErrorsLogger()
    {
        $helper = new AppLoggingHelper([
            'file_path'=>  APP_LOG_FILE_ERRORS,
            'channel_name' => self::LOG_CHANNEL_ERRORS,
            'log_level' =>Logger::ERROR
         ]);
        $logger =  $helper->getAppLogger();
        return $logger;
    }
}
