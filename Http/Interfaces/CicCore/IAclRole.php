<?php

namespace App\Http\Interfaces\CicCore;

interface IAclRole {
    
    public function attachRolesToUser($roles, $userId);
    public function detachRolesFromUser($roles, $userId);
    public function getRolesByUserId($userId);
    public function getAllRoles();
    public function getAllRolesWithPermissions();
    public function syncUserRoles($roles, $userId);
}
