<?php

return [
  'databases' => [
    array(
      'id'      => 1, // identifies database within an application
      'name'    => 'LDAP Service', // short name for UI
      'desc'    => 'Description for the LDAP Service', // description for UI
      'base'    => 'dc=example,dc=org', // default base DN
      'bind'    => [
        'dn' => 'cn=admin,dc=example,dc=org', // default bind DN
        'password' => 'admin'                       // default bind password
      ],

      // 'factory' => '\Korowai\Component\Ldap\Adapter\ExtLdap\AdapterFactory'

      'server'  => [
        'host'  => 'ldap-service' // server's host address
      ]
    ),
    // You may define more LDAP servers
  ]
];
