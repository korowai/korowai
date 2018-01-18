<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace App\Http\Api\Controllers;

use Illuminate\Http\Request;
use Korowai\Framework\Http\Api\Controllers\Controller;

/**
 * Example controller
 */
class ExampleController extends Controller
{
    public function get(Request $request)
    {
        return $this->response->array(['name' => 'example controller']);
    }
}
// vim: syntax=php sw=4 ts=4 et:
