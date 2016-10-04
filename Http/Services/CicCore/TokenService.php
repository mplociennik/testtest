<?php

namespace App\Http\Services\CicCore;

use App\Http\Interfaces\CicCore\IToken;
use App\Http\Entities\Cic\Token;

class TokenService implements IToken {

    protected $entityManager;

    public function __construct(ManagerRegistry $managerRegistry) {
        $this->entityManager = $managerRegistry->getManager();
    }
    
    public function generateTokanForUser($user) {
        return hash('sha256', $user->getId().date("Y-m-d H:i:s"));
    }

    public function saveTokenForUser($user, $token) {
        $newTokenObject = new Token();
        $newTokenObject->setToken($token);
        $newTokenObject->setCreatedAt(date("Y-m-d H:i:s"));
        $newTokenObject->setUpdatedAt(date("Y-m-d H:i:s"));
        $newTokenObject->setUser($user);
        //$this->entityManager->
        //(date("Y-m-d H:i:s"));        
    }

}
