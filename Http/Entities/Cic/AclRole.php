<?php

namespace App\Http\Entities\Cic;

use \Doctrine\ORM\Mapping as ORM;
use \LaravelDoctrine\ACL\Contracts\Role as RoleContract;

/**
 * @ORM\Entity()
 * @ORM\Table(name="acl_roles")
 */
class AclRole implements RoleContract {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    protected $slug;

    /**
     * @ORM\Column(type="string")
     */
    protected $description;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $updated_at;

    /**
     * @ORM\ManyToMany(targetEntity="AclPermission")
     * @ORM\JoinTable(name="acl_permission_role",
     *      joinColumns={@ORM\JoinColumn(name="acl_role_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="acl_permission_id", referencedColumnName="id")}
     *      )
     */
    private $permissions;

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSlug() {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @return datetime
     */
    public function getCreatedAt() {
        return $this->created_at;
    }

    /**
     * @return datetime
     */
    public function getUpdatedAt() {
        return $this->updated_at;
    }

    public function setPermissions($permissions) {
        $this->permissions = $permissions;
    }

    public function getPermissions() {
        return $this->permissions;
    }

    public function getPermissionsArray() {
        $permissionsArray = [];
        foreach ($this->permissions as $permission) {

            array_push($permissionsArray, [
                'id' => $permission->getId(),
                'name' => $permission->getName()
            ]);
        }
        return $permissionsArray;
    }

    public function hasPermissionTo($permissionName) {
        $state = false;
        foreach ($this->permissions as $permission) {
            if ($permission->getName() === $permissionName) {
                $state = true;
            } 
        }
        return $state;
    }

}
