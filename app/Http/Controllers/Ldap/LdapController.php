<?php

namespace App\Http\Controllers\Ldap;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

use Korowai\Component\Ldap\Exception\LdapException;
use Korowai\Component\Ldap\Exception\AttributeException;
use Korowai\Component\Ldap\Ldap;

use Symfony\Component\HttpKernel\Exception\HttpException;

class LdapController extends Controller
{
  /** @var array */
  protected $factories;
  /** @var array */
  protected $ldaps;

  public function __construct(array $factories)
  {
    $this->factories = $factories;
    $this->ldaps = array();
  }

  protected function getLdap(string $connection)
  {
    if(!isset($this->ldaps[$connection])) {
      $factory = $this->factories[$connection];
      $this->ldaps[$connection] = Ldap::createWithAdapterFactory($factory);
    }
    return $this->ldaps[$connection];
  }

  protected function validateBindArgs(Request $request)
  {
    $request->validate([
      'bind.dn' => 'nullable|string',
      'bind.password' => 'nullable|string'
    ]);

    return $this->getLdap()->
  }

  protected function bindHandler(Request $request)
  {
    $this->validateBindArgs($request);
  }

  public function bind(Request $request)
  {
  }
}
