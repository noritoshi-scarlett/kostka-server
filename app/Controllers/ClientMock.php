<?php

namespace App\Controllers;

/**
 * Home Controller.
 */
class ClientMock extends BaseController
{

    /**
     * Display index page.
     *
     * @return void
     */
    public function index()
    {
        echo view('mocks/client_mock.html');
    }
}