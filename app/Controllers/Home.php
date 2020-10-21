<?php

namespace App\Controllers;

use App\Models\UserModel;

/**
 * Home Controller.
 */
class Home extends BaseController
{

    /**
     * Display index page.
     *
     * @return void
     */
    public function index()
    {
        if ($this->logged) {
            $userModel = new UserModel();
            $data['activity'] = (object) [
                'current' => $userModel->getActivity(60 * 10), // 10 min
                'last'    => $userModel->getActivity(60 * 60 * 48) // 48h
            ];
            $this->showPage('index_home', $data);
        } else {
            helper('form');
            $this->showPage('index_sign', []);
        }
    }

    /**
     * Display index page.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function signOut()
    {
        $this->session->destroy();

        return redirect()->to('/');
    }

    /**
     * Sgn up user by giver data.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function signUp()
    {
        if ($this->logged || 'post' !== $this->request->getMethod()) {
            return redirect()->to('/');
        }

        $rules = [
            'username'         => 'required|min_length[3]|checkForUniqueUsername|alpha_numeric_space',
            'email'            => 'required|valid_email|checkForUniqueEmail',
            'password'         => 'required|min_length[7]',
            'password_confirm' => 'required|matches[password]'
        ];
        $errors = [
            'username' => [
                'checkForUniqueUsername' => 'Messages.SignUpError.NotUniqueUsername',
            ],
            'email'    => [
                'checkForUniqueEmail' => 'Messages.SignUpError.NotUniqueEmail',
            ]
        ];
        if (!$this->validate($rules, $errors)) {
            $this->session->setFlashdata('signUpErrors',
                    $this->validator->listErrors());
            return redirect()->to('/');
        }

        $userModel = new UserModel();
        $userId = $userModel->insert([
            'username' => $this->request->getPost('username',
                    FILTER_SANITIZE_STRING),
            'email'    => $this->request->getPost('email', FILTER_SANITIZE_EMAIL),
            'password' => $this->request->getPost('password')
        ]);
        if ($userId) {
            $userData = $userModel->find($userId);
            $this->setSessionForSignIn($userData);
        } else {
            $this->session->setFlashdata('error',
                    lang('Messages.SignUpError.NotValid'));
        }
        return redirect()->to('/');
    }

    /**
     * Sign in user by given data.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function signIn()
    {
        if ($this->logged || 'post' !== $this->request->getMethod()) {
            return redirect()->to('/');
        }

        $rules = [
            'login'    => 'required|authorizeUser[password]',
            'password' => 'required|min_length[7]',
        ];
        $errors = [
            'login' => [
                'authorizeUser' => 'Messages.SignInError.InvalidData'
            ]
        ];
        if (!$this->validate($rules, $errors)) {
            $this->session->setFlashdata('signInErrors',
                    $this->validator->listErrors());
            return redirect()->to('/');
        }

        $userModel = new UserModel();
        $findUser = $userModel
                ->where('email', $this->request->getPost('login'))
                ->orWhere('username', $this->request->getPost('login'))
                ->first();
        if ($findUser) {
            $this->setSessionForSignIn($findUser);
        } else {
            $this->session->setFlashdata('signInErrors',
                    lang('Messages.SignInError.NotFound'));
        }
        return redirect()->to('/');
    }

    /**
     * Start session (sign in).
     *
     * @param object $userData User data from database.
     *
     * @return void
     */
    private function setSessionForSignIn(object $userData): void
    {
        $this->session->set([
                'username'         => $userData->username,
                'user_id'          => $userData->id,
                'permission_level' => $userData->permission,
                'activity'         => $userData->last_activity
            ]);
            $this->session->setFlashdata('success',
                    lang('Messages.SignInSuccess.Logged'));
    }
}