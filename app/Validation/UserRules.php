<?php

namespace App\Validation;

class UserRules
{

    /**
     * Check given username for unique in database.
     *
     * @param string $username  Username.
     * @param string $fields    Field names.
     * @param array  $data      Data from request.
     *
     * @return bool
     */
    public function checkForUniqueUsername(string $username, string $fields,
            array $data): bool
    {
        $userModel = new \App\Models\UserModel();
        return !(bool) $userModel
                        ->where(['LOWER(username)' => strtolower($username)])
                        ->first();
    }

    /**
     * Check given e-mail for unique in database.
     *
     * @param string $email     E-mail.
     * @param string $fields    Field names.
     * @param array  $data      Data from request.
     *
     * @return bool
     */
    public function checkForUniqueEmail(string $email, string $fields,
            array $data): bool
    {
        $userModel = new \App\Models\UserModel();
        return !(bool) $userModel
                        ->where(['email' => strtolower($email)])
                        ->first();
    }

    /**
     * Check given data for exist in database.
     *
     * @param string $login     Email or username.
     * @param string $fields    Field names.
     * @param array  $data      Data from request.
     *
     * @return bool
     */
    public function authorizeUser(string $login, string $fields, array $data): bool
    {
        $userModel = new \App\Models\UserModel();
        $userExist = $userModel
                ->where('email', strtolower($login))
                ->orWhere('username', $login)
                ->first();
        if ($userExist) {
            return password_verify($data['password'], $userExist->password);
        }
        return false;
    }

}
