<?php

return [
    'host' => env('LDAP_HOST', '192.168.1.1'),
    'port' => (int) env('LDAP_PORT', 389),
    'domain' => env('LDAP_DOMAIN', 'kpk.go.id'),
    'base_dn' => env('LDAP_BASE_DN', 'DC=kpk,DC=go,DC=id'),
    'bind_user' => env('LDAP_BIND_USER', ''),
    'bind_password' => env('LDAP_BIND_PASSWORD', ''),
];
