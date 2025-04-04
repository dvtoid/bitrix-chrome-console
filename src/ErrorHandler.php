<?php

namespace BitrixChromeConsole;

use Bitrix\Main\UI\Extension;

class ErrorHandler
{
    protected static $instance;
    protected string $error = '';
    protected bool $debug = false;

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    public function debug(bool $debug)
    {
        $this->debug = $debug;
    }

    public function set(string $error)
    {
        $this->error = $error;
    }

    public function check(): bool
    {
        if ($this->error) {
            if ($this->debug == true) {
                Extension::load("ui.alerts");
                echo "<div class='ui-alert ui-alert-danger'>
                    <span class='ui-alert-message'><strong>BitrixChromeConsole&nbsp;error</strong> $this->error</span>
                 </div>";
            }
            return false;
        }
        return true;
    }
}