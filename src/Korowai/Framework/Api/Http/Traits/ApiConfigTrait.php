<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Framework\Api\Http\Traits;

trait ApiConfigTrait
{
    /**
    * @var array
    */
    static $apiConfigGetDomainMethods = array(
        '_SERVER[SERVER_NAME]',
        '_SERVER[SERVER_ADDR]',
        '_SERVER[HTTP_HOST]'
    );

    /**
    * @var array
    */
    static $apiConfigGetSchemeMethods = array(
        '_SERVER[HTTPS]',
        '_SERVER[REQUEST_SCHEME]'
    );

    public function apiConfigDetermineUrlBase()
    {
        $urlBase = '';
        if(($apiPrefix = config('api.prefix')) && ($apiPrefix != '/')) {
            $urlBase = '/' . ltrim($apiPrefix, '/');
        }
        if(($apiDomain = $this->apiConfigDetermineDomain())) {
            $scheme = $this->apiConfigDetermineScheme();
            $urlBase = $scheme . '://' . $apiDomain . $urlBase;
        }
        return $urlBase;
    }

    public function apiConfigDetermineDomain()
    {
        if(!($domain = config('api.domain'))) {
            if((bool)($methods = $this->apiConfigReadGetDomain())) {
                $domain = $this->apiConfigDetectDomain($methods);
            }
        }
        return $domain;
    }

    public function apiConfigDetermineScheme()
    {
        if(!($scheme = config('api.scheme'))) {
            if(!($methods = $this->apiConfigReadGetScheme())) {
                $methods = static::$apiConfigGetSchemeMethods;
            }
            $scheme = $this->apiConfigDetectScheme($methods);
        }
        return $scheme;
    }

    protected function apiConfigDetectDomain(array $methods)
    {
        foreach($methods as $method) {
            if(in_array($method, static::$apiConfigGetDomainMethods)) {
                if(preg_match('/^_SERVER\[(?P<key>SERVER_NAME|SERVER_ADDR|HTTP_HOST)\]$/', $method, $matches)) {
                    $key = $matches['key'];
                    if((bool)($domain = $_SERVER[$key])) {
                        return $domain;
                    }
                } else {
                    throw \RuntimeException("Internal error. Unable to use method '$method' for API domain detection");
                }
            } else {
                throw \RuntimeException("Unsupported method '$method' for API domain detection");
            }
        }
        return null;
    }

    protected function apiConfigDetectScheme(array $methods) : string
    {
        foreach($methods as $method) {
            switch($method) {
                case '_SERVER[HTTPS]':
                    if((bool)($https = $_SERVER['HTTPS'] ?? null) && $https != 'off') {
                        return 'https';
                    }
                    break;
                case '_SERVER[REQUEST_SCHEME]':
                    if((bool)($scheme = $_SERVER['REQUEST_SCHEME'] ?? null)) {
                        return $scheme;
                    }
                    break;
                default:
                    throw \RuntimeException("Unsupported method '$method' for API scheme detection");
                    break;
            }
        }
        return 'http';
    }

    protected function apiConfigValidateTokens(string $key, array $supportedTokens, $tokens)
    {
        foreach($tokens ?? [] as $token) {
            $this->apiConfigValidateToken($key, $supportedTokens, $token);
        }
    }

    protected function apiConfigValidateToken(string $key, array $supportedTokens, string $token)
    {
        if(!in_array($token, $supportedTokens)) {
            throw new \RuntimeException("Configuration error. Unsupported token '$token' for config('$key'). Check your '.env' and 'config/api.php' files");
        }
    }

    protected function apiConfigSplitTokenList($configValue)
    {
        if($configValue) {
            return array_map('trim', explode('|', $configValue));
        }
        return $configValue;
    }

    protected function apiConfigParseTokenList(string $key, array $supportedTokens, $configValue)
    {
        $methods = $this->apiConfigSplitTokenList($configValue);
        $this->apiConfigValidateTokens($key, $supportedTokens, $methods);
        return $methods;
    }

    protected function apiConfigReadTokenList(string $key, array $supportedTokens)
    {
        return $this->apiConfigParseTokenList($key, $supportedTokens, config($key));
    }

    protected function apiConfigReadGetDomain()
    {
        return $this->apiConfigReadTokenList('api.getDomain', static::$apiConfigGetDomainMethods);
    }

    protected function apiConfigReadGetScheme()
    {
        return $this->apiConfigReadTokenList('api.getScheme', static::$apiConfigGetSchemeMethods);
    }
}
// vim: syntax=php sw=4 ts=4 et:
