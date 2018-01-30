<?php

return [
  'databases' => [
    array(
      'id'      => 1, // identifies database within an application
      'name'    => 'LDAP Service', // short name for UI
      'desc'    => 'Description for the LDAP Service', // description for UI
      'base'    => 'dc=example,dc=org', // default base DN
      'binddn'  => 'cn=admin,dc=example,dc=org', // default bind DN
      //'bindpw'  => 'admin', // default bind password

      // Connection configuration
      'host'  => 'ldap-service' // host address
    ),
    // You may define more LDAP servers
  ]
];
