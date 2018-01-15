<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Framework\Http\Api\Mappings;

interface HalMapping extends ApiMapping
{
    /**
     * Returns an array of curies.
     */
    public function getCuries() : array;
}
// vim: syntax=php sw=4 ts=4 et:
