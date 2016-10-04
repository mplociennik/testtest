<?php

namespace App\Http\Entities\Cic;

use \Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="cic_modules")
 */
class CicModule {

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
     * @ORM\Column(type="boolean")
     */
    protected $status;

    /**
     * @ORM\OneToMany(targetEntity="AclPermission", mappedBy="module")
     * @var ArrayCollection|AclPermissions[]
     */
    protected $permissions;

    public function __construct() {
        $this->permissions = new ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getPermissions() {
        return $this->permissions;
    }

}
