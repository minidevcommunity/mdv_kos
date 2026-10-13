<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index()
    {
        $data = [
            'user' => [
                'id'       => session()->get('user_id'),
                'username' => session()->get('username'),
                'email'    => session()->get('email'),
                'name'     => session()->get('name'),
            ],
        ];

        return view('dashboard', $data);
    }
}
