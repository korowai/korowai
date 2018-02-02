<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

use Korowai\Framework\Ldap\LdapService;

if (! function_exists('ldap') ) {
  /**
   * @param string $dbId
   * @return \Korowai\Framework\Ldap\LdapService|\Korowai\Framework\Ldap\LdapInstance
   */
  function ldap(string $dbId = null)
  {
      $service = app(LdapService::class);
      return null === $dbId ? $service : $service->getInstance($dbId);
  }
}


// vim: syntax=php sw=4 ts=4 et:
