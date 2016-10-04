<?php

namespace App\Http\Controllers\Billings;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\Billings\IBillings;


class BillingsCroneController extends Controller {

    protected $billings;

    public function __construct(IBillings $billings) {
        $this->$billings = $billings;
    }

    public function index() {

        return 'true';
    }

}
