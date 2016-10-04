<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AclGroup
 * @package App\Http\Models
 */
class AclGroup extends Model
{
    /**
     * @var string
     */
    protected $table = 'acl_groups';

    /**
     * @var array
     */
    protected $fillable = array(
        'name', 'description'
    );

    /**
     * @var boolean
     */
    public $timestamps = false;

    /**
    * @return Illuminate\Database\Eloquent\Model
    */
    public function users()
    {
        return $this->belongsToMany('User', 'acl_user_groups', 'group_id', 'user_id');
    }

    /**
    * @return Illuminate\Database\Eloquent\Model
    */
    public function permissions()
    {
        return $this->belongsToMany('AclPermission', 'acl_group_permissions', 'group_id', 'permission_id');
    }
}