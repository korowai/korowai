<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Framework\Http\Api\Controllers;

use Illuminate\Http\Request;
use Korowai\Framework\Http\Api\Controllers\Controller;
use Korowai\Framework\Model\DatabaseConfig;

/**
 * @todo Write documentation for DatabaseController
 */
class DatabaseConfigController extends Controller
{
    public function __construct()
    {
    }

    public function get(Request $request, int $id)
    {
        $databases = DatabaseConfig::findById($id);
        throw new \RuntimeException("hehe: " . var_export($databases, true));
        // FIXME: ensure, it's unique
        return $this->response->array($databases[0]);
    }
}
// vim: syntax=php sw=4 ts=4 et:
