<?php namespace App\Http\Services\Ldap;

/**
 * Class AdConnector
 * @package App\Http\Services\Ldap
 */
class AdConnector
{
    /**
     * @param string $host
     *
     * @return mixed
     */
    public function checkHost($host)
    {
        $connect = ldap_connect($host, config('ldap.port'));
        ldap_set_option($connect, LDAP_OPT_PROTOCOL_VERSION, 3) or die('Unable to set LDAP protocol version');
        ldap_set_option($connect, LDAP_OPT_REFERRALS, 0);
        if ($connect) {
            @ldap_bind($connect, config('ldap.user'), config('ldap.password'));
            return $connect;
        }
        else
            throw new \Exception('Ldap connection not possible');
    }

    /**
     * @return mixed
     */
    public function connect()
    {
        $connect = $this->checkHost(config('ldap.host1'));
        if ($connect == false)
            $connect = $this->checkHost(config('ldap.host2'));

        return $connect;
    }
}