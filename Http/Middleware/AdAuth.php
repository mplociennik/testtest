<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Services\Ldap\AdConnector;
use Illuminate\Contracts\Auth\Guard;
use Doctrine\Common\Persistence\ManagerRegistry as ManagerRegistry;
use App\Http\Models\User;

/**
 * Class AdAuth
 * @package App\Http\Middleware
 */
class AdAuth extends AdConnector {

    /**
     * @var Guard
     */
    protected $auth;

    /**
     * @var Adlogin
     */
    protected $adLogin;

    /**
     * @var User
     */
    protected $user;

    /**
     * @param  Guard   $auth
     * @param  Adlogin $adLogin
     * @param  User    $user
     *
     * @return void
     */
    public function __construct(Guard $auth, ManagerRegistry $mr, User $user) {
        $this->auth = $auth;
        $this->mr = $mr;
        $this->user = $user;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next) {
        // if ($this->auth->guest()) {
        // }

        $login = $this->getAdUser();
        $memberof = $this->result($login)[0]['memberof'];
        
        foreach ($memberof as $entry) {
            $temp = explode(",", $entry);
            if ($temp[0] == config('ldap.memberof')) {
                $user = $this->getUser($login);
                \Auth::loginUsingId($user['id']);

                return $next($request);
            }
        }

        return response('Unauthorized.', 401);
    }

    /**
     * @param string $login
     *
     * @return string
     */
    public function filter($login) {
        return '(&(objectCategory=person)(samaccountname=' . $login . '))';
    }

    /**
     * @return string
     */
    private function getIp() {
        $ip = \Request::getClientIp();
        if($ip = '127.0.0.1'){
            $ip = '10.0.3.115';
        }
        return $ip;
    }

    /**
     * @param string $login
     *
     * @return User $user
     */
    private function getUser($login) {
        $user = $this->user->where('login', $login)->first();
        if (empty($user))
            \App::abort(404, 'Can\'t find login in database!');
        return $user;
    }

    /**
     * @return string
     */
    public function getAdUser() {
        $entityManager = $this->mr->getManager('adlogin');
        $user = $entityManager->createQueryBuilder()
                ->select('al')->from('App\Http\Entities\Cic\AdLogin', 'al')
                ->where('al.ip = :ip')
                ->setParameter(':ip', $this->getIp())
                ->orderBy('al.timestamp')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();
        if (empty($user) || $user->getStatus() === false) {
            \App::abort(404, 'You have a problem with access to application!');
        }

        return $user->getUser();
    }

    /**
     * @param string $next
     *
     * @return mixed
     */
    public function result($login) {
        $results = ldap_search($this->connect(), config('ldap.base_dn'), $this->filter($login), config('ldap.attributes'));

        return ldap_get_entries($this->connect(), $results);
    }

}
