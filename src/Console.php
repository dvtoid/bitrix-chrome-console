<?php

namespace BitrixChromeConsole;

use Bitrix\Main\EventManager;
use Exception;
use PhpConsole\Handler;
use PhpConsole\Helper;

class Console
{
    public static function init(): void
    {
        EventManager::getInstance()->addEventHandler('main', 'OnPageStart', [__CLASS__, "onPageStart"]);
    }

    public static function onPageStart()
    {
        new static();
    }

    protected function __construct()
    {
        try {
            $config = new Config($this);
            if (!$config->checkSettings()) {
                return;
            }

            $config->setStorage();

            $connector = new Connector($config);
            $connector->configure();
            if (!$connector->isActiveClient()) {
                return;
            }

            Helper::register($connector);

        } catch (Exception $e) {
            ErrorHandler::getInstance()->set($e->getMessage());
        }
    }

    /**
     * @param mixed $var
     * @param string $tags
     * @param bool $trace
     * @return void
     */
    public static function log(mixed $var = '', string $tags = '', bool $trace = false): void
    {
        if (!ErrorHandler::getInstance()->check()) {
            return;
        }
        if ($trace) {
            Config::enableTrace();
        }
        $tags = $tags ?: Config::getPathForTag();
        Handler::getInstance()->debug($var, $tags);
    }
}