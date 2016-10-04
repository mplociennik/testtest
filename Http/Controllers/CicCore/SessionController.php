<?php

namespace App\Http\Controllers\CicCore;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class SessionController extends Controller {

    public function index() {
        return \Response::json(Session::get('user'), 200);
    }

}
