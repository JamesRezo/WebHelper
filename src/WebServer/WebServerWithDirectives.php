<?php

namespace JamesRezo\WebHelper\WebServer;

use JamesRezo\WebHelper\Directive\DirectiveInterface;

abstract class WebServerWithDirectives extends WebServer
{
    private $directives;

    public function __construct($name, $version = null)
    {
        $this->directives = array();
        parent::__construct($name, $version);
    }

    public function hasDirectives()
    {
        return true;
    }

    public function setDirective($name, DirectiveInterface $value)
    {
        $this->directives[$name] = $value;

        return $this;
    }

    public function getDirective($name)
    {
        return isset($this->directives[$name]) ? $this->directives[$name] : null;
    }
}
