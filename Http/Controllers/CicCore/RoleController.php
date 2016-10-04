<?php

namespace App\Http\Controllers\CicCore;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Interfaces\CicCore\IAclRole;
use App\Http\Interfaces\CicCore\IAclPermission;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller {

    protected $authUser;
    protected $aclRole;
    protected $aclPermission;

    public function __construct(IAclRole $aclRole, IAclPermission $aclPermission) {
        $this->authUser = \Auth::user();
        $this->aclRole = $aclRole;
        $this->aclPermission = $aclPermission;
    }

    public function getAllRolesByUserId($userId) {
        if (Gate::allows('admin_panel') || $this->authUser->rolesHasPermission('admin_panel')) {
            $allRoles = $this->aclRole->getAllRoles();
            $userRoles = $this->buildUserRolesArray($this->aclRole->getRolesByUserId($userId));
            $response = $this->buildAllRolesArrayByUserId($allRoles, $userRoles);
            \Log::info(get_class($this) . ': ' . __FUNCTION__ . '. UID: ' . $this->authUser->getId(), ['userId' => $userId]);
        } else {
            $response = "Permission denied!";
            \Log::warning(get_class($this) . ': ' . __FUNCTION__ . '. UID: ' . $this->authUser->getId(), ['userId' => $userId]);
            abort(403, 'Permission denied!');
        }
        return $response;
    }

    public function storeRolePermissions(Request $request) {
        if (Gate::allows('admin_panel') || $this->authUser->rolesHasPermission('admin_panel')) {
            $permissions = $request->input('permissions');
            $roleId = $request->input('roleId');
            if (empty($permissions)) {
                $response = $this->aclPermission->detachAllRolePermissions($roleId);
            } else {
                $response = $this->aclPermission->syncRolesPermissions($permissions, $roleId);
                \Log::info(get_class($this) . ': ' . __FUNCTION__ . '. UID: ' . $this->authUser->getId(), ['permissions' => $permissions, 'roleId' => $roleId]);
            }
        } else {
            $response = "Permission denied!";
            \Log::warning(get_class($this) . ': ' . __FUNCTION__ . '. UID: ' . $this->authUser->getId(), ['permissions' => $permissions, 'roleId' => $roleId]);
            abort(403, 'Permission denied!');
        }
        return $response;
    }

    private function buildAllRolesArrayByUserId($allRoles, $userRoles) {

        $allRolesByUserArray = [];
        foreach ($allRoles as $role) {
            $checked = $this->userHaveRole($role['id'], $userRoles);
            array_push($allRolesByUserArray, [
                'id' => $role['id'],
                'name' => $role['name'],
                'description' => $role['description'],
                'checked' => $checked
            ]);
        }

        return $allRolesByUserArray;
    }

    private function userHaveRole($roleId, $userRoles) {
        $state = false;
        foreach ($userRoles as $role) {
            if ($role['id'] === $roleId) {
                $state = true;
            }
        }
        return $state;
    }

    private function buildUserRolesArray($collection) {
        $rolesArray = [];
        foreach ($collection as $role) {
            array_push($rolesArray, [
                'id' => $role->getId(),
                'name' => $role->getName(),
                'description' => $role->getDescription()
            ]);
        }
        return $rolesArray;
    }

    public function getAllRolesWithPermissionsChecked() {
        if (Gate::allows('admin_panel') || $this->authUser->rolesHasPermission('admin_panel')) {
            $allPermissions = $this->aclPermission->getAllPermissions();
            $rolesPermissions = $this->aclRole->getAllRolesWithPermissions();
            $response = $this->buildRolesWithPermissionsArray($allPermissions, $rolesPermissions);
            \Log::info(get_class($this) . ': ' . __FUNCTION__ . '. UID: ' . $this->authUser->getId(), ['allPermissions' => $allPermissions, 'rolesPermissions' => $rolesPermissions]);
        } else {
            $response = "Permission denied!";
            \Log::warning(get_class($this) . ': ' . __FUNCTION__ . '. UID: ' . $this->authUser->getId(), ['allPermissions' => $allPermissions, 'rolesPermissions' => $rolesPermissions]);
            abort(403, 'Permission denied!');
        }
        return $response;
    }

    private function buildRolesWithPermissionsArray($allPermissions, $rolesPermissions) {
        $rolesWithPermissionsArray = [];
        foreach ($rolesPermissions as $role) {
            array_push($rolesWithPermissionsArray, [
                'id' => $role['id'],
                'name' => $role['name'],
                'description' => $role['description'],
                'permissions' => $this->buildRolePermissionsArray($allPermissions, $role['permissions'])
            ]);
        }

        return $rolesWithPermissionsArray;
    }

    private function buildRolePermissionsArray($allPermissions, $rolePermissions) {
        $allPermissionsByRoleArray = [];

        foreach ($allPermissions as $permission) {
            $checked = $this->roleHavePermission($permission['id'], $rolePermissions);
            array_push($allPermissionsByRoleArray, [
                'id' => $permission['id'],
                'name' => $permission['name'],
                'description' => $permission['description'],
                'checked' => $checked,
                'group_id' => $permission['group_id']
            ]);
        }

        return $allPermissionsByRoleArray;
    }

    private function roleHavePermission($permissionId, $rolePermissions) {
        $state = false;
        foreach ($rolePermissions as $permission) {
            if ($permission['id'] === $permissionId) {
                $state = true;
            }
        }
        return $state;
    }

}
