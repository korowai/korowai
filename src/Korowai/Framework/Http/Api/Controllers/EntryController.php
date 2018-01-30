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
use Illuminate\Support\Collection;
use Korowai\Framework\Http\Api\Controllers\Controller;
use Korowai\Framework\Model\Entry;
use Korowai\Framework\Http\Api\Transformers\EntryTransformer;

/**
 * @todo Write documentation for EntryController
 */
class EntryController extends Controller
{
    const RESOURCE_KEY = 'entry';

    public function __construct()
    {
    }
//
//    public function index(Request $request)
//    {
//        $databases = Entry::all();
//        return $this->response->collection(
//            new Collection($databases),
//            new EntryTransformer,
//            ['key' => static::RESOURCE_KEY]
//        );
//    }

    public function show(Request $request, int $server, string $dn)
    {
        $config = config('ldap.databases')[$server-1];
        $ldap = \Korowai\Component\Ldap\Ldap::createWithConfig(['host' => $config['host']]);
        $ldap->bind($config['binddn'], 'admin');
        $entry = Entry::findByDn($ldap, $dn);
        return $this->response->item(
            $entry,
            new EntryTransformer,
            ['key' => static::RESOURCE_KEY]
        );
    }
}
// vim: syntax=php sw=4 ts=4 et:
