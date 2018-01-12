<?php

namespace App\Http\Controllers\Ldap;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

use Korowai\Component\Ldap\Exception\LdapException;
use Korowai\Component\Ldap\Exception\AttributeException;
use Korowai\Component\Ldap\Ldap;

use Symfony\Component\HttpKernel\Exception\HttpException;

use App\Korowai\LdapAdapterFactories;

class AuthController extends Controller
{
  /** @var LdapAdapterFacties */
  protected $adapterFactories;
  /** @var array */
  protected $ldaps;

  public function __construct(LdapAdapterFactories $adapterFactories)
  {
    $this->adapterFactories = $adapterFactories;
    $this->ldaps = array();
  }

  protected function getLdap(string $key)
  {
    if(!isset($this->ldaps[$key])) {
      // FIXME: implement error raising (when invalid $key)
      $factory = $this->adapterFactories[$key];
      $this->ldaps[$key] = Ldap::createWithAdapterFactory($factory);
    }
    return $this->ldaps[$key];
  }

  protected function validateBindArgs(Request $request)
  {
    $request->validate([
      'bind.db' => 'required|string',
      'bind.dn' => 'nullable|string',
      'bind.password' => 'nullable|string'
    ]);
  }


  protected function bindHandler(Request $request)
  {
    $this->validateBindArgs($request);

    $ldap = $this->getLdap($request->input('bind.db'));
    return $ldap->bind($request->input('bind.dn'), $request->input('bind.password'));
  }

  protected function prepareJsonResponse($result = null)
  {
    $extra = array();

    // FIXME:implement...
    if(isset($result) && !is_object($result)) {
      $extra['result'] = $result;
      $status = 200;
    } else {
      $status = 201;
    }

    return response()->json([
      'status' => $code,
      'message' => $message
    ] + $extra, $status);
  }

  public function bind(Request $request)
  {
    $result = $this->bindHandler($request);
    return $this->prepareJsonResponse($result);
  }
}
