<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Framework\Api\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Dingo\Api\Exception\Handler as DingoExceptionHandler;
use Korowai\Framework\Api\Http\Middleware\Middleware;

class SetErrorReplacements extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        app(DingoExceptionHandler::class)->setReplacements([
            ':request_path' => $request->path(),
            ':request_query' => (config('api.debug') ? $request->query() : null),
            ':request_headers' => (config('api.debug') ? $request->header() : null),
        ]);
        return $next($request);
    }
}
// vim: syntax=php sw=4 ts=4 et:
