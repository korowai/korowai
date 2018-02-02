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

    public function show(Request $request, int $id)
    {
        $databases = Database::findById($id);
        if(($n = count($databases)) < 1) {
            return $this->response->errorNotFound();
        } elseif ($n > 1) {
            return $this->response->error("Ambiguous search result", 404);
        }
        return $this->response->item(
            $databases[0],
            new DatabaseTransformer,
            ['key' => static::RESOURCE_KEY]
        );
    }
}
// vim: syntax=php sw=4 ts=4 et:
