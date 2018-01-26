<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Framework\Http\Api\Transformers;

use Korowai\Framework\Model\DatabaseConfig;
use League\Fractal\TransformerAbstract;

class DatabaseConfigTransformer extends TransformerAbstract
{
    public function transform(DatabaseConfig $db)
    {
        return $db->toArray();
    }
}
// vim: syntax=php sw=4 ts=4 et:
