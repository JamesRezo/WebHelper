<?php

/**
 * This file is, guess what, part of WebHelper.
 *
 * (c) James <james@rezo.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JamesRezo\WebHelper\Directive;

/**
 * DirectiveInterface is the interface implemented by all directive classes.
 *
 * @author James <james@rezo.net>
 */
abstract class AbstractDirective implements DirectiveInterface
{
    private $name;

    private $availability;

    private $value;

    private $context;

    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getAvailabilty()
    {
        return $this->availability;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getContext()
    {
        return $this->context;
    }
}
