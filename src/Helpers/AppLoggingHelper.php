<?php

namespace Vanier\Api\Helpers;

use DateTimeZone;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\WebProcessor;

class AppLoggingHelper
{
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
    public static function CreateAppLogger($options)
    {
        $helper = new AppLoggingHelper($options);
        $logger =  $helper->getAppLogger();
        return $logger;
    }
}
