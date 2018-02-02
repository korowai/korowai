<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Framework\Api\Http\Traits;

use Illuminate\Http\Request;

trait RequestHelpersTrait
{
    /**
     * Retrieve a list of parameters (uri path components) from request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function getRequestParams(Request $request) : array
    {
        if(($route = $request->route()) instanceof Route) {
            // Laravel
            return $route->parameters();
        } elseif(is_array($route)) {
            // Lumen
            return $route[2];
        } else {
            // God damn!
            return array();
        }
    }

    /**
     * Get named parameter (uri path component) from request.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $key
     * @return string|null
     */
    public function getRequestParam(Request $request, string $key)
    {
        return $this->getRequestParams($request)[$key] ?? null;
    }
}
// vim: syntax=php sw=4 ts=4 et:
