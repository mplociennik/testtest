<?php

namespace App\Http\Services\CicCore;

use App\Http\Interfaces\CicCore\IAclPermission;
use Doctrine\Common\Persistence\ManagerRegistry;
use LaravelDoctrine\ACL\Permissions\Permission as Permission;
use App\Http\Entities\Cic\Group;
use LaravelDoctrine\ACL\Permissions\PermissionManager;

class AclPermissionService implements IAclPermission {

    protected $permissionManager;
    protected $entityManager;

    public function __construct(PermissionManager $permissionManager, ManagerRegistry $managerRegistry) {
        $this->permissionManager = $permissionManager;
        $this->entityManager = $managerRegistry->getManager();
    }

    public function attachPermissionsToUser($permissions, $userId) {
        $user = $this->entityManager->find("App\Http\Entities\Cic\User", $userId);
        $permissionsObjects = $this->getPermissionCollectionByPermissionsIds($permissions);
        foreach ($permissionsObjects as $permission) {
            if (!$user->getPermissions()->contains($permission)) {
                $user->getPermissions()->add($permission);
            }
        }
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function detachPermissionsFromUser($permissions, $userId) {
        $user = $this->entityManager->find("App\Http\Entities\Cic\User", $userId);
        $permissionsObjects = $this->getPermissionCollectionByPermissionsIds($permissions);
        foreach ($permissionsObjects as $permission) {
            if ($user->getPermissions()->contains($permission)) {
                $user->getPermissions()->removeElement($permission);
            }
        }
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function getPermissionsByUserId($userId) {
        $user = $this->entityManager
                        ->createQueryBuilder()
                        ->select('u', 'm', 'p', 'g')
                        ->from('App\Http\Entities\Cic\User', 'u')
                        ->innerJoin('u.permissions', 'p')
                        ->innerJoin('p.module', 'm')
                        ->innerJoin('p.group', 'g')
                        ->where('u.id = :userid')
                        ->andWhere('m.status = :status')
                        ->setParameter('userid', $userId)
                        ->setParameter('status', 1)
                        ->getQuery()->getOneOrNullResult();

        return $user ? $user->getPermissions()->getValues() : array();
    }

    public function getPermissionsByGroupId($groupId) {
        $group = $this->entityManager->find("Group", $groupId);
        return $group->getResult();
    }

    private function getPermissionCollectionByPermissionsIds($permissionsIds) {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('p');
        $qb->from('App\Http\Entities\Cic\AclPermission', 'p');
        $qb->where($qb->expr()->in('p.id', $permissionsIds));
        return $qb->getQuery()->getResult();
    }

    public function getAllPermissions() {
        $permissions = $this->entityManager->createQueryBuilder();
        $permissions->select('p')
                ->from('App\Http\Entities\Cic\AclPermission', 'p');
        return $permissions->getQuery()->getArrayResult();
    }

    public function syncUserPermissions($permissions, $userId) {
        $user = $this->entityManager->find("App\Http\Entities\Cic\User", $userId);
        $permissionsObjects = $this->getPermissionCollectionByPermissionsIds($permissions);
        $user->setPermissions($permissionsObjects);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return 'User permissions has been updated!';
    }

    public function syncRolesPermissions($permissions, $roleId) {
        $role = $this->entityManager->find("App\Http\Entities\Cic\AclRole", $roleId);
        $permissionsObjects = $this->getPermissionCollectionByPermissionsIds($permissions);
        $role->setPermissions($permissionsObjects);
        $this->entityManager->persist($role);
        $this->entityManager->flush();
        return 'Roles permissions has been updated!';
    }

    public function detachAllRolePermissions($roleId) {
        $role = $this->entityManager->find("App\Http\Entities\Cic\AclRole", $roleId);
        foreach ($role->getPermissions() as $permission) {
            $role->getPermissions()->removeElement($permission);
        }
        $this->entityManager->persist($role);
        $this->entityManager->flush();
        return 'Role permissions has been deleted!';
    }
    
    public function detachAllUserPermissions($userId) {
        $user = $this->entityManager->find("App\Http\Entities\Cic\User", $userId);
        foreach ($user->getPermissions() as $permission) {
            $user->getPermissions()->removeElement($permission);
        }
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return 'User permissions has been deleted!';
    }

}
