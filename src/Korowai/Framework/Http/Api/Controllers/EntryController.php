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

    public function show(Request $request, string $db, string $dn)
    {
        $entry = Entry::findByDn(rawurldecode($db), rawurldecode($dn));
        return $this->response->item(
            $entry,
            new EntryTransformer,
            ['key' => static::RESOURCE_KEY]
        );
    }
}
// vim: syntax=php sw=4 ts=4 et:
