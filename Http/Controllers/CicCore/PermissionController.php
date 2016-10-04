<?php

namespace App\Http\Controllers\CicCore;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\CicCore\IAclPermission;
use Illuminate\Support\Facades\Gate;

/**
 * Description of PermissionController
 *
 * @author owojno
 */
class PermissionController extends Controller {

    protected $aclPermission;
    protected $authUser;

    public function __construct(IAclPermission $aclPermission) {
        $this->authUser = \Auth::user();
        $this->aclPermission = $aclPermission;
        
    }

    public function getAllPermissionsByUserId($userId) {
        if ($this->authUser->hasPermissionTo('admin_panel') || $this->authUser->rolesHasPermission('admin_panel')) {
            $allPermissions = $this->aclPermission->getAllPermissions();
            $userPermissions = $this->buildUserPermissionsArray($this->aclPermission->getPermissionsByUserId($userId));
            $response = $this->buildAllPermissionsArrayByUserId($userId, $allPermissions, $userPermissions);
            \Log::info(get_class($this) . ': ' .__FUNCTION__ . '. UID: '. $this->authUser->getId(), ['userId'=>$userId]);
        } else {
            $response = "Permission denied!";
            \Log::warning(get_class($this) . ': ' .__FUNCTION__ . '. UID: ' . $this->authUser->getId(), ['userId'=>$userId]);
            abort(403, 'Permission denied!');
        }
        return $response;
    }

    private function buildAllPermissionsArrayByUserId($userId, $allPermissions, $userPermissions) {

        $allPermissionsByUserArray = [];
        foreach ($allPermissions as $permission) {
            $checked = $this->userHavePermission($permission['id'], $userPermissions);
            array_push($allPermissionsByUserArray, [
                'id' => $permission['id'],
                'name' => $permission['name'],
                'description' => $permission['description'],
                'group_id' => $permission['group_id'],
                'checked' => $checked
            ]);
        }

        return $allPermissionsByUserArray;
    }

    private function userHavePermission($permissionId, $userPermissions) {
        $state = false;
        foreach ($userPermissions as $permission) {
            if ($permission['id'] === $permissionId) {
                $state = true;
            }
        }
        return $state;
    }

    private function buildUserPermissionsArray($collection) {
        $permissionsArray = [];
        foreach ($collection as $permission) {
            array_push($permissionsArray, [
                'id' => $permission->getId(),
                'name' => $permission->getName(),
                'description' => $permission->getDescription(),
                'group' => [
                    'id' => $permission->getGroupId(),
                    'name' => $permission->getGroup()->getName(),
            ]]);
        }
        return $permissionsArray;
    }

}
