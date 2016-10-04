<?php

namespace App\Http\Controllers\CicCore;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Interfaces\CicCore\IGroup;

class GroupController extends Controller {

    private $authUser;
    private $group;

    public function __construct(IGroup $group) {
        
        $this->authUser = \Auth::user();
        $this->group = $group;
    }

    public function index() {
        if (Gate::allows('admin_panel') || $this->authUser->rolesHasPermission('admin_panel')) {
            $response = $this->group->getAllGroups();
        } else {
            $response = "Permission denied!";
        }
        return $response;
    }
}
