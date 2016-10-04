<?php

namespace App\Http\Controllers\CicCore;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\Dashboard\IDashboardComponent as Dashboard;
use App\Http\Interfaces\CicCore\IAclPermission;
//use App\Http\Interfaces\CicCore\IToken;
use Illuminate\Support\Facades\Session;

/**
 * Description of CicController
 *
 * @author owojno
 */
class CicController extends Controller {

    protected $dashboard;
    protected $aclPermission;
    protected $tokens;

    public function __construct(Dashboard $dashboard, IAclPermission $aclPermission) {
        $this->dashboard = $dashboard;
        $this->aclPermission = $aclPermission;
        //   $this->tokens = $tokens;
    }

    public function index() {

        $userFromAd = \Auth::user();

        $user = [
            'id' => $userFromAd->getId(),
            'login' => $userFromAd->getLogin(),
            'firstname' => $userFromAd->getFirstname(),
            'lastname' => $userFromAd->getLastname(),
            'consultant' => $userFromAd->getConsultant(),
            'supervisor' => $userFromAd->getSupervisor(),
            'status' => $userFromAd->getStatus(),
            'department' => $userFromAd->getDepartment(),
            'email' => $userFromAd->getMail(),
            'avatar' => $userFromAd->getAvatar(),
            'profession' => $userFromAd->getProfession(),
            'trans_id' => $userFromAd->getTransId(),
            'permissions' => $this->aclPermission->getPermissionsByUserId($userFromAd->getId()),
            'roles' => $userFromAd->getRolesArray()
        ];
        //  if(!Session::has('token')) {
        //    $token = $this->tokens->generateTokenForUser($user);
        //       Session::put('token', $token);
        //      $this->tokens->saveTokenForUser($user, $token);
        //   }
        Session::put('user', $user);
        Session::save();
        \Log::info(get_class($this) . ': ' . __FUNCTION__ . '. UID: ' . $userFromAd->getId());

        return $this->dashboard->getDashboard();
    }

    public function user() {
        $user = \Auth::user();
        return response()->json();
    }

}
