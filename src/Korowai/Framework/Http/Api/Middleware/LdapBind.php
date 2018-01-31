<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @license Distributed under MIT license.
 */

namespace Korowai\Framework\Http\Api\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;

class LdapBind
{
    use \Dingo\Api\Routing\Helpers;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(($route = $request->route()) instanceof Route) {
            $params = $route->parameters();
        } elseif(is_array($route)) {
            $params = $route[2];
        } else {
            $params = array();
        }

        if(is_string($dbid = $params['db'] ?? null)) {
            try {
                $ldap = app('ldap.db.' . $dbid);
            } catch (\ReflectionException $e) {
                if($e->getMessage() == 'Class ldap.db.' . $dbid . ' does not exist') {
                    return $this->response->errorNotFound('LDAP database ' . $dbid . ' not found');
                } else {
                    throw $e;
                }
            }

            if(!$ldap->isBound()) {
                if( ($b64h = $request->query('ldapBind')) ||
                    ($b64h = $request->header('x-korowai-ldap-bind'))) {
                    if(!isset($json = base64_decode($b64h, true))) {
                        // FIXME: handle NULL $json (error)
                    }
                    if(!isset($args = json_decode($json, true, 2))) {
                        // FIXME: handle NULL $args (error)
                    }
                } else {
                    $args = [];
                }
                $ldap->bind(...$args);
            }
        }

        return $next($request);
    }
}
// vim: syntax=php sw=4 ts=4 et:
