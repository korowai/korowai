<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Framework\Model;


class DatabaseConfig extends \ArrayObject
{
    public $id;
    public $host;
    public $port;
    public $uri;
    public $encryption;
    public $options;
}
// vim: syntax=php sw=4 ts=4 et:
