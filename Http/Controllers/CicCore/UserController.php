<?php

namespace App\Http\Controllers\CicCore;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Interfaces\CicCore\IUser;
use App\Http\Interfaces\CicCore\IAclRole;
use App\Http\Interfaces\CicCore\IAclPermission;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller {

    private $authUser;
    private $user;
    private $role;
    private $permission;

    public function __construct(IUser $user, IAclRole $role, IAclPermission $permission) {

        $this->authUser = \Auth::user();
        $this->user = $user;
        $this->role = $role;
        $this->permission = $permission;
    }

    public function index() {
        if (Gate::allows('admin_panel') || $this->authUser->rolesHasPermission('admin_panel')) {
            $response = $this->user->getAllUsers();
            \Log::info(get_class($this) . ': ' .__FUNCTION__ . '. UID: ' . $this->authUser->getId());
        } else {
            $response = "Permission denied!";
            \Log::warning(get_class($this) . ': ' .__FUNCTION__ . '. UID: ' . $this->authUser->getId());
            abort(403, 'Permission denied!');
        }
        return $response;
    }

    public function storeUserRoles(Request $request) {
        if (Gate::allows('admin_panel') || $this->authUser->rolesHasPermission('admin_panel')) {
            $roles = $request->input('roles');
            $userId = $request->input('userId');
            $response = $this->role->syncUserRoles($roles, $userId);
            \Log::info(get_class($this) . ': ' .__FUNCTION__ . '. UID: ' . $this->authUser->getId(), ['roles'=>$roles, 'userId'=>$userId]);
        } else {
            $response = "Permission denied!";
            \Log::warning(get_class($this) . ': ' .__FUNCTION__ . '. UID: ' . $this->authUser->getId(), ['roles'=>$roles, 'userId'=>$userId]);
            abort(403, 'Permission denied!');
        }
        return $response;
    }

    public function storeUserPermissions(Request $request) {
        if (Gate::allows('admin_panel') || $this->authUser->rolesHasPermission('admin_panel')) {
            $permissions = $request->input('permissions');
            $userId = $request->input('userId');
            if (empty($permissions)) {
                $response = $this->permission->detachAllUserPermissions($userId);
            } else {
                $response = $this->permission->syncUserPermissions($permissions, $userId);
                \Log::info(get_class($this) . ': ' .__FUNCTION__ . '. UID: ' . $this->authUser->getId(), ['permissions'=>$permissions, 'userId'=>$userId]);
            }
        } else {
            $response = 'Permission denied!';
            \Log::warning(get_class($this) . ': ' .__FUNCTION__ . '. UID: ' . $this->authUser->getId(), ['permissions'=>$permissions, 'userId'=>$userId]);
            abort(403, 'Permission denied!');
        }
        return $response;
    }

}
