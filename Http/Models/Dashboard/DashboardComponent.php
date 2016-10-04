<?php

namespace App\Http\Models\Dashboard;

use App\Http\Interfaces\Dashboard\IDashboardComponent;

class DashboardComponent implements IDashboardComponent {

    protected $message;
    
    // $this->message = 'dupa';
    
    public function __construct() {
        $this->message = 'dupa';
    }

    public function getDashboard() {
        return view('dashboard.index')->with('message', $this->message);
    }

}
