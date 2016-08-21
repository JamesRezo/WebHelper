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
interface DirectiveInterface
{
    public function getName();

    public function getAvailabilty();

    public function getValue();

    public function getContext();
}
