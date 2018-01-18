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
use Korowai\Framework\Http\Api\Transformers\DatabaseConfigTransformer;

/**
 * @todo Write documentation for DatabaseController
 */
class DatabaseConfigController extends Controller
{
    public function __construct()
    {
    }

    public function getConfigById(Request $request, int $id)
    {
        // FIXME: ensure somehow a consistency of $key with an appropriate route
        $key = 'config/database';
        $databases = DatabaseConfig::findById($id);
        return $this->response->item($databases[0], new DatabaseConfigTransformer, ['key' => $key]);
    }
}
// vim: syntax=php sw=4 ts=4 et:
