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
use Korowai\Framework\Api\Http\Middleware\Middleware;

class LdapBind extends Middleware
{
    const BIND_ARGNAME = 'ldapBind';
    const BIND_HDRNAME = 'x-korowai-ldap-bind';

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
     * Get from $request serialized arguments for LDAP bind() method.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $source
     * @return string|null
     */
    protected function getSerializedBindArgs(Request $request, &$source)
    {
        if( null !== ($json = $request->query(static::BIND_ARGNAME)) ) {
            $source = "'" . static::BIND_ARGNAME . "' argument";
        } else {
            if( null !== ($b64 = $request->header(static::BIND_HDRNAME)) ) {
                $source = "'" . static::BIND_HDRNAME . "' header";
                if(false === ($json = base64_decode($b64, true))) {
                    $msg = "Invalid base64 string in $source: " . var_export($b64,true);
                    throw $this->response->errorBadRequest($msg);
                }
            }
        }
        return $json;
    }

    /**
     * Unserialize LDAP bind() arguments returned by getSerializedBindArgs().
     *
     * @param string|null $json
     * @param string|null $source
     * @return array
     */
    protected function unserializeBindArgs($json, $source) : array
    {
        if(isset($json)) {
            if(null === ($args = json_decode($json, true, 2))) {
                $msg ="Malformed JSON in $source: " . var_export($json,true);
                throw $this->response->errorBadRequest($msg);
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
            return ldap($dbId);
        } catch (\ReflectionException $e) {
            if($e->getMessage() == "Class ldap.db.$dbId does not exist") {
                throw $this->response->errorNotFound("LDAP database '$dbId' not found");
            } else {
                throw $e;
            }
        }
    }
}
// vim: syntax=php sw=4 ts=4 et:
