<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Framework\Http\Api\Mappings;

use NilPortugues\Api\Mappings\HalMapping;
use Korowai\Framework\Model\DatabaseConfig;

class DatabaseConfigMapping implements HalMapping
{
  public function getClass() : string { return DatabaseConfig::class; }
  public function getAlias() : string { return ""; }
  public function getAliasedProperties() : array { return []; }
  public function getHideProperties() : array { return []; }
  public function getIdProperties() : array { return ['id']; }

  public function getUrls() : array
  {
    return [
      'self' => ['name' => 'get_database_config'] // named route
    ];
  }

  public function getCuries() : array
  {
    return [ ];
  }
}
// vim: syntax=php sw=4 ts=4 et:
