<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Adlogin
 * @package App\Http\Models
 */
class Adlogin extends Model
{
    /**
     * @var string
     */
    protected $connection = 'adlogin';

    /**
     * @var string
     */
    protected $table = 'ad_login';

    /**
     * @var boolean
     */
    public $timestamps = false;
    
}
