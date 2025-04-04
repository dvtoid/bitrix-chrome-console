<?php

namespace BitrixChromeConsole;

use PhpConsole\Connector as CConnector;
use PhpConsole\Handler;

class Connector extends CConnector
{
    private CConnector $connector;
    private array $settings;

    public function __construct(Config $config)
    {
        $this->connector = CConnector::getInstance();
        $this->settings = $config->getSettings();
    }

    public function configure(): void
    {
        $this->configureSsl();
        $this->configurePassword();
        $this->configureBasePath();
        $this->configureAllowedIps();
        $this->configureEvalTerminal();

        $dumper = $this->connector->getDumper();
        $dumper->itemsCountLimit = 5000;
        $dumper->levelLimit = 10;
    }

    private function configureSsl(): void
    {
        if (!!$this->settings["ssl"]) {
            $this->connector->enableSslOnlyMode();
        }
    }

    private function configurePassword(): void
    {
        if (!empty($this->settings["password"])) {
            $this->connector->setPassword($this->settings["password"]);
        }
    }

    private function configureBasePath(): void
    {
        if (!empty($this->settings["base_path"])) {
            $this->connector->setSourcesBasePath($this->settings["base_path"]);
        }
    }

    private function configureAllowedIps(): void
    {
        if (!empty($this->settings["allowed_ips"]) && is_array($this->settings["allowed_ips"])) {
            $this->connector->setAllowedIpMasks($this->settings["allowed_ips"]);
        }
    }

    private function configureEvalTerminal(): void
    {
        if (!!$this->settings["terminal"]) {
            $handler = Handler::getInstance();
            $handler->setHandleErrors(false);
            $handler->setHandleExceptions(true);
            $handler->setCallOldHandlers(true);
            $handler->start();

            $this->connector->startEvalRequestsListener();
        }
    }
}