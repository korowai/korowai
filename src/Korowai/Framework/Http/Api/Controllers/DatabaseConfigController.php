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
        $databases = config('ldap.databases');
        $array = $databases[$id-1];
        $db = new DatabaseConfig();
        $db->id = $array['id'];
        $db->name = $array['name'];
        $keys = ['desc', 'base', 'host', 'port', 'uri', 'encryption', 'options'];
        foreach($keys as $key) {
            if(isset($array[$key])) {
                $db->$key = $array[$key];
            }
        }
        return $this->response->array($db);
    }
}
// vim: syntax=php sw=4 ts=4 et:
