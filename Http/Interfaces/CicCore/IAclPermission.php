<?php

namespace App\Http\Interfaces\CicCore;

interface IAclPermission {
    
    public function attachPermissionsToUser($permissions, $userId);
    public function detachPermissionsFromUser($permissions, $userId);
    public function getPermissionsByUserId($userId);
    public function getPermissionsByGroupId($groupId);
    public function getAllPermissions();
    public function syncUserPermissions($permissions, $userId);
    public function syncRolesPermissions($permissions, $roleId);
    public function detachAllRolePermissions($roleId);
    public function detachAllUserPermissions($userId);
}
