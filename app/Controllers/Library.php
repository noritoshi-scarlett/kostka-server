<?php

namespace App\Controllers;

use App\Models\UserModel;

/**
 * Library Controller.
 */
class Library extends BaseController
{

    /**
     * Display ist of items.
     *
     * @return void
     */
    public function index()
    {
        if (!$this->isLogged) {
            return redirect()->to('/');
        }

        $this->showPage('library_index', []);
    }

}
