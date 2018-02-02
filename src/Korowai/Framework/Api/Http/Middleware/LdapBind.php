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
use Korowai\Framework\Api\Http\Middleware\Middleware;

class LdapBind extends Middleware
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
        if(is_string($dbId = $this->getRequestParam($request, 'db'))) {

            $ldap = $this->getLdapDb($dbId);

            if(!$ldap->isBound()) {
                $args = $this->getBindArgs($request);
                $ldap->bind(...$args);
            }
        }

        return $next($request);
    }

    /**
     * Get arguments for LDAP bind() method.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    protected function getBindArgs(Request $request) : array
    {
        $json = $this->getSerializedBindArgs($request, $source);
        return $this->unserializeBindArgs($json, $source);
    }

    /**
     * Get arguments for LDAP bind() method in a serialized format.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $source
     * @return string|null
     */
    protected function getSerializedBindArgs(Request $request, &$source)
    {
        if( null === ($json = $request->query('ldapBind')) ) {
            if( null !== ($b64h = $request->header('x-korowai-ldap-bind')) ) {
                $source = "'X-Korowai-Ldap-Bind' header";
                if(false === ($json = base64_decode($b64h, true))) {
                    throw $this->response->errorBadRequest("Invalid base64 string in $source: " . var_export($b64h,true));
                }
            }
        } else {
            $source = "'ldapBind' parameter";
        }
        return $json;
    }

    /**
     * Unserialize arguments for LDAP bind returned from getSerializedBindArgs().
     *
     * @param string|null $json
     * @param string $source
     * @return array
     */
    protected function unserializeBindArgs($json, string $source) : array
    {
        if(isset($json)) {
            if(null === ($args = json_decode($json, true, 2))) {
                throw $this->response->errorBadRequest("Malformed JSON in $source: " . var_export($json,true));
            }
        } else {
            $args = [];
        }
        return $args;
    }

    /**
     * Return an instance of LDAP interface for database identified by $dbId.
     *
     * @param string $dbId
     * @return \Korowai\Component\Ldap\LdapInterface
     */
    protected function getLdapDb(string $dbId)
    {
        try {
            return app('ldap.db.' . $dbId);
        } catch (\ReflectionException $e) {
            if($e->getMessage() == "Class ldap.db.$dbId does not exist") {
                throw $this->response->errorNotFound("LDAP database '$dbId' not found");
            } else {
                throw $e;
            }
        }
    }

    /**
     * Retrieve a list of parameters (uri path components) from request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    protected function getRequestParams(Request $request) : array
    {
        if(($route = $request->route()) instanceof Route) {
            return $route->parameters();
        } elseif(is_array($route)) {
            return $route[2];
        } else {
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
    protected function getRequestParam(Request $request, string $key)
    {
        return $this->getRequestParams($request)[$key] ?? null;
    }
}
// vim: syntax=php sw=4 ts=4 et:
