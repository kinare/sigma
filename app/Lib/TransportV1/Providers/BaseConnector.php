<?php

class BaseConnector
{
    protected $connPrefix;
    const connParams = [];
    const configParams = [];
    const flatAPIDirectory = [];
    const docAPIDirectory = [];

    public function __construct()
    {

        $this->connPrefix = env ('SIGMA_CONNECTOR_PREFIX');
    }

    protected function setConnectionParams()
    {

    }
    protected function setConnectionConfigs()
    {

    }

    protected function deriveFlatConnectors()
    {

    }
    protected function deriveDocConnectors()
    {

    }

}
