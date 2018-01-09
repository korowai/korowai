<?php

namespace App\Http\Controllers\Ldap;

use App\Http\Controllers\Controller;

use Korowai\Component\Ldap\Exception\LdapException;
use Korowai\Component\Ldap\Exception\AttributeException;
use Korowai\Component\Ldap\LdapInterface;

use Symfony\Component\HttpKernel\Exception\HttpException;

class LdapController extends Controller
{
  /** @var array */
  protected $factories;

  public function __construct(array $factories)
  {
    $this->factories = $factories;
  }
}
