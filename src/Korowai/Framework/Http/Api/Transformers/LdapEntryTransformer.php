<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Framework\Http\Api\Transformers;

use Korowai\Framework\Model\LdapEntry;
use League\Fractal\TransformerAbstract;

class LdapEntryTransformer extends TransformerAbstract
{
    public function transform(LdapEntry $entry)
    {
        return $entry->toArray();
    }
}
// vim: syntax=php sw=4 ts=4 et:
