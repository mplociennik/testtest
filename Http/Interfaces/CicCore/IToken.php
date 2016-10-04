<?php
namespace App\Http\Interfaces\CicCore;
interface IToken {
    public function saveTokenForUser($user, $token);
}
