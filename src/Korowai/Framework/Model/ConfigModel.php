<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Framework\Model;

use ArrayAccess;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Routing\UrlRoutable;

abstract class ConfigModel implements ArrayAccess, Jsonable, Arrayable, UrlRoutable
{
    use Illuminate\Database\Eloquent\Concerns\HasAttributes;

    /**
     * Creates ConfigModel instance
     *
     * @param array $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    /**
     * Fill object with an array of attributes
     *
     * @param array $attributes
     * @return $this
     */
    public function fill(array $attributes)
    {
        return $this;
    }
}
// vim: syntax=php sw=4 ts=4 et:
