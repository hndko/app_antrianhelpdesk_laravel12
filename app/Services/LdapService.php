<?php

namespace App\Services;

class LdapService
{
    protected string $host;
    protected int $port;
    protected string $domain;
    protected string $baseDn;

    public function __construct()
    {
        $this->host = config('ldap.host', '192.168.1.1');
        $this->port = (int) config('ldap.port', 389);
        $this->domain = config('ldap.domain', 'kpk.go.id');
        $this->baseDn = config('ldap.base_dn', 'DC=kpk,DC=go,DC=id');
    }

    /**
     * Authenticate a user by binding to the LDAP/AD directory.
     *
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function authenticate(string $username, string $password): bool
    {
        if (empty($password)) {
            return false;
        }

        // Format username to UPN (e.g. username@kpk.go.id) if not already formatted
        $bindUser = $username;
        if (!str_contains($username, '@') && !str_contains($username, '\\')) {
            $bindUser = $username . '@' . $this->domain;
        }

        $ldapconn = @ldap_connect($this->host, $this->port);
        if (!$ldapconn) {
            return false;
        }

        @ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
        @ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

        $bind = @ldap_bind($ldapconn, $bindUser, $password);

        if ($bind) {
            @ldap_close($ldapconn);
            return true;
        }

        @ldap_close($ldapconn);
        return false;
    }

    /**
     * Search users in Active Directory using the administrator's credentials.
     *
     * @param string $bindUsername
     * @param string $bindPassword
     * @param string $query
     * @return array
     */
    public function searchUsers(string $bindUsername, string $bindPassword, string $query): array
    {
        $ldapconn = @ldap_connect($this->host, $this->port);
        if (!$ldapconn) {
            throw new \Exception("Gagal terhubung ke LDAP Server {$this->host}:{$this->port}");
        }

        @ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
        @ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

        $bindUser = $bindUsername;
        if (!str_contains($bindUsername, '@') && !str_contains($bindUsername, '\\')) {
            $bindUser = $bindUsername . '@' . $this->domain;
        }

        $bind = @ldap_bind($ldapconn, $bindUser, $bindPassword);
        if (!$bind) {
            throw new \Exception("Kredensial AD Bind salah atau gagal menghubungkan ke Active Directory.");
        }

        // Filter: active users only
        $filter = "(&(objectClass=user)(objectCategory=person)(!(userAccountControl:1.2.840.113556.1.4.803:=2)))";
        if (!empty($query)) {
            $escapedQuery = $this->escapeLdapFilter($query);
            $filter = "(&(objectClass=user)(objectCategory=person)(!(userAccountControl:1.2.840.113556.1.4.803:=2))(|(sAMAccountName=*{$escapedQuery}*)(displayName=*{$escapedQuery}*)(mail=*{$escapedQuery}*)))";
        }

        $attributes = ['samaccountname', 'displayname', 'mail', 'cn'];
        $search = @ldap_search($ldapconn, $this->baseDn, $filter, $attributes);

        if (!$search) {
            @ldap_close($ldapconn);
            return [];
        }

        $entries = @ldap_get_entries($ldapconn, $search);
        @ldap_close($ldapconn);

        $results = [];
        if ($entries && isset($entries['count'])) {
            for ($i = 0; $i < $entries['count']; $i++) {
                $entry = $entries[$i];
                $username = $entry['samaccountname'][0] ?? null;
                $name = $entry['displayname'][0] ?? $entry['cn'][0] ?? $username;
                $email = $entry['mail'][0] ?? ($username ? $username . '@' . $this->domain : null);

                if ($username) {
                    $results[] = [
                        'username' => $username,
                        'name' => $name,
                        'email' => $email,
                    ];
                }
            }
        }

        return $results;
    }

    /**
     * Escape special characters in LDAP filter.
     */
    protected function escapeLdapFilter(string $value): string
    {
        return str_replace(
            ['\\', '*', '(', ')', "\x00"],
            ['\\5c', '\\2a', '\\28', '\\29', '\\00'],
            $value
        );
    }
}
