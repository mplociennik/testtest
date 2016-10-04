<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use App\Http\Models\Adlogin;
use App\Http\Models\User;

class AclPermitted
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth, Adlogin $adLogin, User $user)
    {
            echo "<pre>"; 
    var_dump('apud');
    echo "<hr>";
    die;
        $this->auth = $auth;
        $this->adLogin = $adLogin;
        $this->user = $user;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // if ($this->auth->guest()) {

        // }
        $login = $this->getAdUser();
        $memberof = $this->result($login)[0]['memberof'];
        foreach ($memberof as $entry) {
            $temp = explode(",", $entry);
            if ($temp[0] == \Config::get('ldap.memberof')) {
                $user = $this->getUser($login);
                \Auth::login($user);
                return $next($request);
            }
        }

        return response('Unauthorized.', 401);
    }

    public function filter($login)
    {
        return '(&(objectCategory=person)(samaccountname=' . $login . '))';
    }

    private function getIp()
    {
        return \Request::getClientIp();
    }
    
    private function getUser($login)
    {
        $user = $this->user->where('login', $login)->first();
        if(empty($user))
            \App::abort(404, 'Can\'t find login in database!');
        
        return $user;
    }

    public function getAdUser()
    {
        $user = $this->adLogin->select('user', 'status')
            ->where('ip',$this->getIp())
            ->orderBy('timestamp', 'desc')
            ->first();
        if(empty($user) || $user->status == 0)
            \App::abort(404, 'You have a problem with access to application!');
        
        return $user->user;
    }

    public function result($login)
    {
        $results = ldap_search($this->connect(), \Config::get('ldap.base_dn'), $this->filter($login) ,\Config::get('ldap.attributes'));
        return ldap_get_entries($this->connect(), $results);
    }

}
