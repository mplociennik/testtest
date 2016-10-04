<?php

namespace App\Http\Entities\Cic;

use \Doctrine\ORM\Mapping as ORM;
use \LaravelDoctrine\ACL\Contracts\Permission as PermissionContract;

/**
 * @ORM\Entity
 * @ORM\Table(name="acl_permissions")
 */
class AclPermission implements PermissionContract {

    public function __construct() {
        $this->groups = new ArrayCollection();
    }

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
     * @ORM\ManyToOne(targetEntity="CicModule", inversedBy="permissions")
     * @ORM\JoinColumn(name="module_id", referencedColumnName="id", nullable=FALSE)
     * @var CicModule
     */
    protected $module;

    /**
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="permissions")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id", nullable=FALSE)
     * @var Group
     */
    protected $group;

    /**
     * @ORM\Column(type="integer")
     */
    protected $group_id;

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

    /**
     * @return int
     */
    public function getGroupId() {
        return $this->group_id;
    }

    public function getModule() {
        return $this->module;
    }

    public function getGroup() {
        return $this->group;
    }

}
