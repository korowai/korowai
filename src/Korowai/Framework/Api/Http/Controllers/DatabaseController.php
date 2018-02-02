<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Framework\Api\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Korowai\Framework\Api\Http\Controllers\Controller;
use Korowai\Framework\Model\Database;
use Korowai\Framework\Api\Http\Transformers\DatabaseTransformer;

/**
 * @todo Write documentation for DatabaseController
 */
class DatabaseController extends Controller
{
    const RESOURCE_KEY = 'database';

    public function __construct()
    {
    }

    public function index(Request $request)
    {
        $databases = Database::all();
        return $this->response->collection(
            new Collection($databases),
            new DatabaseTransformer,
            ['key' => static::RESOURCE_KEY]
        );
    }

    public function show(Request $request, string $id)
    {
        if(null == ($database = Database::getById($id))) {
            return $this->response->errorNotFound("LDAP database '$id' not found");
        }
        return $this->response->item(
            $database,
            new DatabaseTransformer,
            ['key' => static::RESOURCE_KEY]
        );
    }
}
// vim: syntax=php sw=4 ts=4 et:
