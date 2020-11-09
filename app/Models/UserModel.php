<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{

    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'username',
        'email',
        'avatar',
        'description',
        'password',
        'settings_get_invitations',
        'settings_get_messages',
        'permission',
        'last_activity'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];
    protected $validationRules = [
        'username' => 'required|min_length[3]|is_unique[users.username]|alpha_numeric_space',
        'email'    => 'required|min_length[5]|is_unique[users.email]|valid_email',
        'password' => 'required|min_length[7]',
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;

    /**
     * Get activity for given interval.
     *
     * @param int $interval Interval from now to last activity.
     *
     * @return object
     */
    public function getActivity(int $interval): ?object
    {
        return (object) $this->builder()
                ->select('id, username, avatar')
                ->getWhere('last_activity >=' . (time() - $interval))
                ->getResult();
    }

    /**
     * Actions before insert data.
     *
     * @param array $data Data for insert.
     *
     * @return type
     */
    protected function beforeInsert(array $data)
    {
        $data['data']['password'] = password_hash(
                $data['data']['password'], PASSWORD_DEFAULT
        );
        $data['data']['email'] = strtolower($data['data']['email']);
        return $data;
    }

    /**
     * Actions before update record.
     *
     * @param array $data Data for update.
     *
     * @return type
     */
    protected function beforeUpdate(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash(
                    $data['data']['password'], PASSWORD_DEFAULT
            );
        }
        if (isset($data['data']['email'])) {
            $data['data']['email'] = strtolower($data['data']['email']);
        }
        return $data;
    }

}
