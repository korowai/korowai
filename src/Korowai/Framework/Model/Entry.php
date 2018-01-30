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
use Korowai\Component\Ldap\LdapInterface;
use Korowai\Component\Ldap\Entry as LdapEntry;

/**
 * Identifies an LDAP entry within the Korowai application. Encapsulates an
 * identifier of the LDAP server containing the entry, and the entry's DN.
 */
class EntryId
{
    /**
     * @var string
     */
    private $serverId;
    /**
     * @var string
     */
    private $dn;

    /**
     * Initializes the EntryId object.
     *
     * @param mixed $serverId Must be convertible to string
     * @param string $dn
     */
    public function __construct($serverId, string $dn)
    {
        $this->serverId = (string)$serverId;
        $this->dn = $dn;
    }

    /**
     * Returns ID converted to an array of strings.
     *
     * @return array
     */
    public function pieces() : array
    {
        return [$this->getServerId(), $this->getDn()];
    }

    /**
     * Represent EntryID as string.
     *
     * @return string
     */
    public function __toString() : string
    {
        return implode('/', $this->pieces());
    }

    /**
     * Returns the server ID part of the entry ID.
     *
     * @return string
     */
    public function getServerId() : string
    {
        return $this->serverId;
    }

    /**
     * Returns the entry DN part of the entry ID.
     *
     * @return string
     */
    public function getDn() : string
    {
        return $this->dn;
    }
};

/**
 * Models an LDAP entry within the korowai application.
 */
class Entry implements Arrayable
{
    /**
     * @var mixed
     */
    private $serverId;
    /**
     * @var \Korowai\Component\Ldap\Entry
     */
    private $entry;

    public static function findByDn(string $serverId, string $dn) : self
    {
        $ldap = app('ldap.db.' . $serverId);
        // FIXME: filter? options? deletate?
        $result = $ldap->query($dn, 'objectclass=*', ['scope' => 'base']);
        return new Entry(1, $result->getEntries()[$dn]);
    }

    public function __construct($serverId, LdapEntry $entry)
    {
        $this->serverId = (string)$serverId;
        $this->entry = $entry;
    }

    /**
     * Returns the entry's DN.
     *
     * @return string
     */
    public function getDn() : string
    {
        return $this->entry->getDn();
    }

    /**
     * Sets the entry's DN
     *
     * @param string $dn
     */
    public function setDn(string $dn)
    {
        $this->entry->setDn($dn);
    }

    /**
     * Returns the complete array of attributes
     *
     * @return array
     */
    public function getAttributes() : array
    {
        return $this->entry->getAttributes();
    }

    /**
     * Returns a specific attribute's values
     *
     * @param string $name
     * @throws AttributeException
     * @return array
     */
    public function getAttribute(string $name) : array
    {
        return $this->entry->getAttribute($name);
    }

    /**
     * Returns whether an attribute exists
     *
     * @param string $name
     * @return bool
     */
    public function hasAttribute(string $name) : bool
    {
        return $this->entry->hasAttribute($name);
    }

    /**
     * Sets attributes.
     *
     * @param array $attributes
     */
    public function setAttributes(array $attributes)
    {
        $this->entry->setAttributes($attributes);
    }

    /**
     * Sets values for the given attribute.
     *
     * @param string $name
     * @param array $values
     */
    public function setAttribute(string $name, array $values)
    {
        $this->entry->setAttribute($name, $values);
    }

    /**
     * Returns this entry's identifier object.
     *
     * @return EntryId
     */
    public function getId() : EntryId
    {
        return new EntryId($this->serverId, $this->entry->getDn());
    }

    /**
     * Returns this entry converted to an array (for serialization).
     *
     * @return array
     */
    public function toArray() : array
    {
        return array(
            'id' => $this->getId(),
            'dn' => $this->getDn(),
            'attributes' => $this->getAttributes()
        );
    }
}
// vim: syntax=php sw=4 ts=4 et:
