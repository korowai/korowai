<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Framework\Api\Http\Transformers;

use Korowai\Framework\Model\Database;
use League\Fractal\TransformerAbstract;

class DatabaseTransformer extends TransformerAbstract
{
    public function transform(Database $db)
    {
        return $db->getPublicConfig();
    }
}
// vim: syntax=php sw=4 ts=4 et:
