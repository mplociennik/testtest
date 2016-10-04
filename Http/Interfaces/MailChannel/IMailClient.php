<?php

namespace App\Http\Interfaces\MailChannel;

interface IMailClient {
    
    public function getMails($mailbox, $username, $password);
    public function getSingleMailById($mailbox, $username, $password, $id);
    //public function 
    
}
