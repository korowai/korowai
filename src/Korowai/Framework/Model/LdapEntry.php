<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Framework\Model;

use Illuminate\Contracts\Support\Arrayable;
use Korowai\Component\Ldap\Entry;
use Korowai\Component\Ldap\LdapInterface;

/**
 * @todo Write documenation for LdapEntry
 */
class LdapEntry implements Arrayable
{
    private $entry;

    public static function findByDn(LdapInterface $ldap, string $dn) : self
    {
        // FIXME: filter? options? deletate?
        $result = $ldap->query($dn, 'objectclass=*', ['scope' => 'base']);
        return new LdapEntry($result->getEntries()[$dn]);
    }

    public function __construct(Entry $entry)
    {
        $this->entry = $entry;
    }

    public function toArray() : array
    {
        return array(
            'id' => $this->getId(),
            'dn' => $this->entry->getDn(),
            'attributes' => $this->entry->getAttributes()
        );
    }

    public function getId()
    {
        return $this->entry->getDn();
    }
}
// vim: syntax=php sw=4 ts=4 et:
