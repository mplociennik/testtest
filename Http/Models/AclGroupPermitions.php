<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AclGroupPermitions
 * @package App\Http\Models
 */
class AclGroupPermitions extends Model
{
	/**
     * @var string
     */
    protected $table = 'acl_group_permissions';
    
    /**
     * @var boolean
     */
    public $timestamps = false;
}