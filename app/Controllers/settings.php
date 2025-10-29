<?php
// app/Controllers/Settings.php
namespace App\Controllers;

use CodeIgniter\Controller;

class Settings extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Settings - Hello World'
        ];

        return view('settings/index', $data);
    }
}