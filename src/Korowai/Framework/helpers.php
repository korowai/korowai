<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @license Distributed under MIT license.
 */

//declare(strict_types=1);

if (! function_exists('ldap') ) {
  /**
   * @param string $dbId
   * @return \Korowai\Component\Ldap\LdapInterface
   */
  function ldap(string $dbId)
  {
    return app('ldap.db.' . $dbId);
  }
}


// vim: syntax=php sw=4 ts=4 et:
