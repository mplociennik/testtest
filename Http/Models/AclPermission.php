<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AclPermission
 * @package App\Http\Models
 */
class AclPermission extends Model
{
	/**
     * @var string
     */
    protected $table = 'acl_permissions';

    /**
     * @var array
     */
    protected $fillable = array(
        'ident', 'description'
    );

    /**
     * @var boolean
     */
    public $timestamps = false;

    /**
    * @return Illuminate\Database\Eloquent\Model
    */
    public function groups()
    {
        return $this->belongsToMany('AclGroup', 'acl_group_permissions', 'permission_id', 'group_id');
    }
}