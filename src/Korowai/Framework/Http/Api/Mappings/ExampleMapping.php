<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Framework\Http\Api\HalMapping;

use NilPortugues\Api\Mappings\HalMapping as HalMappingInterface;
use Korowai\Framework\Model\Example as ExampleModel;

class ExampleMapping implements HalMappingInterface
{
  public function getClass() { return ExampleModel::class; }
  public function getAlias() { return ""; }
  public function getAliasedProperties() { return []; }
  public function getHideProperties() { return []; }
  public function getIdProperties() { return []; }
  public function getUrls()
  {
    return [
      'self' => ['name' => 'get.example']
    ];
  }
  public function getCuries()
  {
    return [
      'name' => 'example',
      'href' => '/example'
    ];
  }
}
// vim: syntax=php sw=4 ts=4 et:
