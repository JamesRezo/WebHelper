<?php

namespace JamesRezo\WebHelper\WebServer;

abstract class WebServer implements WebServerInterface
{
    private $name;

    private $version;

    public function __construct($name, $version = null)
    {
        $this->name = $name;
        $this->version = $version;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getVersion()
    {
        return $this->version;
    }
}
