<?php

namespace App\Http\Services\CicCore;

use App\Http\Interfaces\CicCore\IGroup;
use Doctrine\Common\Persistence\ManagerRegistry;

class GroupService implements IGroup {

    protected $entityManager;

    public function __construct(ManagerRegistry $managerRegistry) {
        $this->entityManager = $managerRegistry->getManager();
    }

    public function getAllGroups() {
        $query = $this->entityManager->createQueryBuilder()
                ->select('g')->from('App\Http\Entities\Cic\Group', 'g')
                ->orderBy('g.name', 'ASC')
                ->getQuery();
        $results = $query->getArrayResult();
        return $results;
    }
    
}
