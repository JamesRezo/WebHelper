<?php

namespace JamesRezo\WebHelper\WebServer;

class ApacheWebServer extends WebServer
{
    public function __construct($version = 0)
    {
        parent::__construct('apache', $version);
    }
}
