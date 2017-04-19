<?php

namespace CoRex\Client\Base;

class ConnectorRequest
{
    public $method = '';
    public $url = '';
    public $tokens = [];
    public $parameters = [];
    public $headers = [];
    public $body = null;
    public $userAgent = '';
}