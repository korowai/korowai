<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Framework\Http\Api\Response\Format;

use Dingo\Api\Http\Response\Format\Json as DingoJsonFormat;

class Json extends DingoJsonFormat
{
    protected function getKorowaiContentType()
    {
        $stdTree = config('api.standardsTree');
        $subtype = $this->request->subtype();
        $version = $this->request->version();
        $format = $this->request->format();
        return "application/$stdTree.$subtype.$version+$format";
    }

    public function getContentType()
    {
        $strict = config('api.strict');
        $acceptHeader = $this->request->header('accept') ;
        $parentContentType = parent::getContentType();
        $korowaiContentType = $this->getKorowaiContentType();

        if(($acceptHeader === '*/*' && $strict) || $acceptHeader === $korowaiContentType) {
            return $acceptHeader;
        } else {
            return $parentContentType;
        }
    }
}
// vim: syntax=php sw=4 ts=4 et:
