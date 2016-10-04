<?php

namespace App\Http\Services\CicCore;

use App\Http\Interfaces\CicCore\IUser;
use Doctrine\Common\Persistence\ManagerRegistry;

class UserService implements IUser {

    protected $entityManager;

    public function __construct(ManagerRegistry $managerRegistry) {
        $this->entityManager = $managerRegistry->getManager();
    }

    public function getAllUsers() {
        $query = $this->entityManager->createQueryBuilder()
                ->select('u')->from('App\Http\Entities\Cic\User', 'u')
                ->where('u.status = 1 AND u.lastname IS NOT NULL AND u.profession IS NOT NULL')
                ->orderBy('u.lastname', 'ASC')
                ->getQuery();
        $users = $query->getArrayResult();
        return $users;
    }
    
}
