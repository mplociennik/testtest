<?php

namespace App\Http\Entities\Cic;

use \Doctrine\ORM\Mapping AS ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use \LaravelDoctrine\ACL\Roles\HasRoles;
use \LaravelDoctrine\ACL\Permissions\HasPermissions;
use LaravelDoctrine\ACL\Mappings as ACL;
use LaravelDoctrine\ACL\Contracts\HasRoles as HasRolesContract;
use LaravelDoctrine\ORM\Contracts\Auth\Authenticatable as DoctrineAuthenticable;
use LaravelDoctrine\ACL\Contracts\HasPermissions as HasPermissionContract;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User implements DoctrineAuthenticable, HasRolesContract, HasPermissionContract {

    use \App\Http\Traits\Cic\Authenticatable,
        HasRoles,
        HasPermissions;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $firstname;

    /**
     * @ORM\Column(type="string")
     */
    protected $lastname;

    /**
     * @ORM\Column(type="string")
     */
    protected $login;

    /**
     * @ORM\Column(type="string")
     */
    protected $mail;

    /**
     * @ORM\Column(type="string")
     */
    protected $avatar;

    /**
     * @ORM\Column(type="string")
     */
    protected $hash;

    /**
     * @ORM\Column(type="string")
     */
    protected $profession;

    /**
     * @ORM\Column(type="integer")
     */
    protected $phone;

    /**
     * @ORM\Column(type="integer")
     */
    protected $mobile;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $consultant;

    /**
     * @ORM\Column(type="string")
     */
    protected $department;

    /**
     * @ORM\Column(type="string")
     */
    protected $branch;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $status;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $status_date;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $supervisor;

    /**
     * @ORM\Column(type="string")
     */
    protected $trans_id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $deleted_at;
    
  //  protected $tokens;

    /**
     * @ORM\ManyToMany(targetEntity="AclPermission")
     * @ORM\JoinTable(name="acl_permission_user",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="acl_permission_id", referencedColumnName="id")}
     *      )
     */
    private $permissions;

    /**
     * @ACL\HasRoles()
     * @var \Doctrine\Common\Collections\ArrayCollection|\LaravelDoctrine\ACL\Contracts\Role[]
     */
    protected $roles;

    public function __construct() {
        $this->permissions = new ArrayCollection();
    //    $this->tokens = new ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getFirstname() {
        return $this->firstname;
    }

    public function setFirstname($firstname) {
        $this->firstname = $firstname;
    }

    public function getLastname() {
        return $this->lastname;
    }

    public function setLastname($lastname) {
        $this->lastname = $lastname;
    }

    public function getLogin() {
        return $this->login;
    }

    public function setLogin($login) {
        $this->login = $login;
    }

    public function getMail() {
        return $this->mail;
    }

    public function setMail($mail) {
        $this->mail = $mail;
    }

    public function getAvatar() {
        return $this->avatar;
    }

    public function setAvatar($avatar) {
        $this->avatar = $avatar;
    }

    public function getHash() {
        return $this->hash;
    }

    public function setHash($hash) {
        $this->hash = $hash;
    }

    public function getProfession() {
        return $this->profession;
    }

    public function setProfession($profession) {
        $this->profession = $profession;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
    }

    public function getMobile() {
        return $this->mobile;
    }

    public function setMobile($mobile) {
        $this->mobile = $mobile;
    }

    public function getConsultant() {
        return $this->consultant;
    }

    public function setConsultant($consultant) {
        $this->consultant = $consultant;
    }

    public function getDepartment() {
        return $this->department;
    }

    public function setDepartment($department) {
        $this->department = $department;
    }

    public function getBranch() {
        return $this->branch;
    }

    public function setBranch($branch) {
        $this->branch = $branch;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getStatusDate() {
        return $this->status_date;
    }

    public function setStatusDate($status_date) {
        $this->status_date = $status_date;
    }

    public function getSupervisor() {
        return $this->supervisor;
    }

    public function setSupervisor($supervisor) {
        $this->supervisor = $supervisor;
    }

    public function getTransId() {
        return $this->trans_id;
    }

    public function setTransId($trans_id) {
        $this->trans_id = $trans_id;
    }

    public function getDeletedAt() {
        return $this->deleted_at;
    }

    public function setDeletedAt($deleted_at) {
        $this->deleted_at = $deleted_at;
    }

    public function getRoles() {
        return $this->roles;
    }
    
    public function setRoles($roles) {
        $this->roles = $roles;
    }

    public function getPermissions() {
        return $this->permissions;
    }

    public function setPermissions($permissions) {
        return $this->permissions = $permissions;
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

    public function getRolesArray() {
        $rolesArray = [];
        foreach ($this->roles as $role) {
            array_push($rolesArray, [
                'id' => $role->getId(),
                'name' => $role->getName(),
                'permissions' => $role->getPermissionsArray()
            ]);
        }
        return $rolesArray;
    }
    
    public function rolesHasPermission($permissionName) {
        $state = false;
        foreach($this->roles as $role){
            if($role->hasPermissionTo($permissionName)){
                $state = true;
            }
        }
        return $state;
    }
    
}
