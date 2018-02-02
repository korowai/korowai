<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Framework\Api\Http\Exceptions;

use Closure;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\Response;
use Korowai\Component\Ldap\Exception\LdapException;

class Handler
{
    protected static  $ldapHttpCodes = [
        0 => 200,  // success
        1 => 500,  // operationsError
        2 => 400,  // protocolError
        3 => 400,  // timeLimitExceeded
        4 => 400,  // sizeLimitExceeded
        5 => 200,  // compareFalse
        6 => 200,  // compareTrue
        7 => 400,  // authMethodNotSupported
        8 => 400,  // strongerAuthRequired

        10 => 200,  // referral
        11 => 400,  // adminLimitExceeded
        12 => 400,  // unavailableCriticalExtension
        13 => 400,  // confidentalityRequired
        14 => 200,  // saslBindInProgress

        16 => 400,  // noSuchAttribute
        17 => 400,  // undefinedAttributeType
        18 => 400,  // inappropriateMatching
        19 => 400,  // constraintViolation
        20 => 400,  // attributeOrValueExists
        21 => 400,  // invalidAttribtueSyntax

        32 => 400,  // noSuchObject
        33 => 400,  // aliasProblem
        34 => 400,  // invalidDNSyntax

        36 => 400,  // aliasDereferencingProblem

        48 => 401,  // inappropriateAuthentication
        49 => 401,  // invalidCredentials
        50 => 403,  // insufficientAccessRights
        51 => 503,  // busy
        52 => 503,  // unavailable
        53 => 400,  // unwillingToPerform
        54 => 400,  // loopDetect

        64 => 400,  // namingViolation
        65 => 400,  // objectClassViolation
        66 => 400,  // notAllowedOnNonLeaf
        67 => 400,  // notAllowedOnRDN
        68 => 400,  // entryAlreadyExists
        69 => 400,  // objectClassModsProhibited

        71 => 400,  // affectsMultipleDSAs

        80 => 500,  // internalError
    ];

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException;
     */
    public static function handleLdapException(LdapException $e)
    {
        $code = $e->getCode();
        $http = static::$ldapHttpCodes[$code] ?? 500;
        throw new HttpException($http, $e->getMessage(), $e, [], $code);
    }
}

// vim: syntax=php sw=4 ts=4 et:
