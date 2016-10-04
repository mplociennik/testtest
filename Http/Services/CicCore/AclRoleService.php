<?php

namespace App\Http\Services\CicCore;

use App\Http\Interfaces\CicCore\IAclRole;
use Doctrine\Common\Persistence\ManagerRegistry;
use LaravelDoctrine\ACL\Roles\Role as Role;

class AclRoleService implements IAclRole {

    protected $entityManager;

    public function __construct(ManagerRegistry $managerRegistry) {
        $this->entityManager = $managerRegistry->getManager();
    }

    public function attachRolesToUser($roles, $userId) {
        $user = $this->entityManager->find("App\Http\Entities\Cic\User", $userId);
        $rolesObjects = $this->getRolesCollectionByRolesIds($roles);
        foreach ($rolesObjects as $role) {
            if (!$user->getRoles()->contains($role)) {
                $user->getRoles()->add($role);
            }
        }
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return true;
    }

    public function detachRolesFromUser($roles, $userId) {
        $user = $this->entityManager->find("App\Http\Entities\Cic\User", $userId);
        $rolesObjects = $this->getRolesCollectionByRolesIds($roles);
        foreach ($rolesObjects as $role) {
            if ($user->getRoles()->contains($role)) {
                $user->getRoles()->removeElement($role);
            }
        }
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function getRolesByUserId($userId) {
        $user = $this->entityManager
                        ->createQueryBuilder()
                        ->select('u', 'r')
                        ->from('App\Http\Entities\Cic\User', 'u')
                        ->innerJoin('u.roles', 'r')
                        ->where('u.id = :userid and u.status = :status')
                        ->setParameter('userid', $userId)
                        ->setParameter('status', 1)
                        ->getQuery()->getOneOrNullResult();

        return $user ? $user->getRoles()->getValues() : array();
    }

    private function getRolesCollectionByRolesIds($rolesIds) {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('r');
        $qb->from('App\Http\Entities\Cic\AclRole', 'r');
        $qb->where($qb->expr()->in('r.id', $rolesIds));
        return $qb->getQuery()->getResult();
    }

    public function getAllRoles() {
        $roles = $this->entityManager->createQueryBuilder();
        $roles->select('r')
                ->from('App\Http\Entities\Cic\AclRole', 'r');
        $results = $roles->getQuery()->getArrayResult();

        return $results;
    }

    public function getAllRolesWithPermissions() {
        $roles = $this->entityManager->createQueryBuilder();
        $roles->select('r', 'p')
                ->from('App\Http\Entities\Cic\AclRole', 'r')
                ->leftJoin('r.permissions', 'p')
                ->orderBy('r.name', 'ASC');
        $results = $roles->getQuery()->getArrayResult();

        return $results;
    }

    public function syncUserRoles($roles, $userId) {
        $user = $this->entityManager->find("App\Http\Entities\Cic\User", $userId);
        $rolesObjects = $this->getRolesCollectionByRolesIds($roles);
        $user->setRoles($rolesObjects);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return 'Data has been updated!';
    }

}
