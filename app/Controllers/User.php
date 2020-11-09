<?php

namespace App\Controllers;

use App\Models\UserModel;

/**
 * User Controller.
 */
class User extends BaseController
{

    /**
     * Display index page.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function view(int $userID)
    {
        if (!$this->logged) {
            return redirect()->to('/');
        }
        $userModel = new UserModel();
        $userData = $userModel->select('id, username, avatar, description')->find($userID);
        $userFriends = null;
        if (!isset($userData)) {
            return redirect()->to('/');
        }
        $this->showPage('user_view', [
            'profile'     => $userData,
            'userForums'  => $this->libraryModel->getSubscribedForumsByUser($userID),
            'userRolls'   => [],
            'userFriends' => $userFriends
        ]);
    }
}