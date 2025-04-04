<?php

namespace BitrixChromeConsole;

use Bitrix\Main\Config\Configuration;
use Closure;
use Exception;
use PhpConsole\Connector;
use PhpConsole\Storage\File;
use PhpConsole\Storage\Session;

class Config
{
    private Console $console;
    private array $settings;

    public function __construct(Console $console)
    {
        $this->console = $console;
        $this->settings = Configuration::getInstance()->get("bitrix_chrome_console");
    }

    public function checkSettings(): bool
    {
        if (!$this->settings || !$this->settings["enabled"]) {
            return false;
        }

        if ($this->settings["debug"] == true) {
            ErrorHandler::getInstance()->debug(true);
        }

        return true;
    }

    public function getSettings()
    {
        return $this->settings;
    }

    public function setStorage()
    {
        if (isset($this->settings['storage'])) {
            switch ($this->settings['storage']['type']) {
                case 'session':
                    $storageName = $this->settings['storage']['name'] ?? 'PHP_CONSOLE_STORAGE';
                    Connector::setPostponeStorage(new Session($storageName, false));
                    break;
                default:
                    $storage_path = $this->settings['storage']['path'] ?: $_SERVER['DOCUMENT_ROOT'] . '/tmp/bc.data';
                    $path = pathinfo($storage_path, PATHINFO_DIRNAME);
                    if (!is_dir($path) && !mkdir($path, 0777, true)) {
                        throw new Exception("Cannot create storage directory '{$path}'");
                    }
                    Connector::setPostponeStorage(new File($storage_path));
                    break;
            }
        }
    }

    public static function getPathForTag(): string
    {
        $debug = debug_backtrace();
        $tags = str_replace($_SERVER['DOCUMENT_ROOT'], '', $debug[1]['file']) . ':' . $debug[1]['line'];

        $conn = Connector::getInstance();
        $closuremethod = Closure::bind(function ($conn) {
            return $conn->sourcesBasePath;
        }, null, $conn);
        $base_path = $closuremethod($conn);

        if ($base_path) {
            $tags = str_replace($base_path, '', $tags);
        }
        return $tags;
    }

    public static function enableTrace(): void
    {
        Connector::getInstance()->getDebugDispatcher()->detectTraceAndSource = true;
    }
}