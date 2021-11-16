<?php
//this is the actual implementation of the Http connection to the various sources of
//membership data e.g. ERP systems, 3rd party apps, JSON Repos etc

interface SigmaConnector {

    public function getConnectorPrefix ();

    /**
     * use this to get all the registered api urls for this connection based on the env configs for this
     * @return mixed
     */
    public function getApiDirectory ();

    public function checkConnectionStatus ();

    public function getDocumentEntry();

    public function getFlatEntry ();

    public function pushDocumentEntry();

    public function pushFlatEntry ();

    public function packageRequestPath ($basePath, $requestUrl, $requestParams = []);

    public function rawRequest($endpoint, array $data = [], $method = 'get');

    public function request($endpoint, array $data = [], $method = 'get');

}
