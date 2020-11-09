<?php

namespace App\Models;

use CodeIgniter\Model;

class LibraryModel extends Model
{

    protected $table = 'forums';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'code_name',
        'icon',
        'domain',
        'name',
        'token',
        'visibility',
        'description',
        'script_type'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];
    protected $validationRules = [
        'code_name'   => 'required|min_length[3]|is_unique[forums.code_name]|alpha_numeric_space',
        'name'        => 'required|min_length[3]',
        'icon'        => 'required|min_length[2]|max_length[8]',
        'domain'      => 'required|min_length[5]',
        'token'       => 'required|min_length[16]|max_length[16]|is_unique[forums.token]|alpha_numeric_space',
        'script_type' => 'required|min_length[3]'
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;

    /**
     * Get unsubscribed list of forums.
     *
     * @param int $userID       User id.
     *
     * @return array|null
     */
    public function getUnSubscribedForumsByUser(int $userID): ?array
    {
        return $this->builder()
                ->select('forums.id, forums.icon, forums.code_name, forums.name, forums.domain')
                ->join('users_forums', 'forums.id = users_forums.forum_id', 'left outer')
                ->where('forums.visibility', 1)
                ->where('users_forums.user_id', null)
                ->orWhereNotIn('users_forums.user_id', [$userID])
                ->get()
                ->getResult();
    }

    /**
     * Get list of subscibed forums.
     *
     * @param int $userID User id.
     *
     * @return array|null
     */
    public function getSubscribedForumsByUser(int $userID): ?array
    {
        return $this->builder()
                ->select('forums.id, forums.icon, forums.code_name, forums.name, forums.domain')
                ->join('users_forums', 'users_forums.forum_id = forums.id')
                ->where('users_forums.user_id', $userID)
                ->get()
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
        $data['visibility'] = false;
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
        if (isset($data['data']['code_name'])) {
            unset($data['data']['code_name']);
        }
        if (isset($data['data']['token'])) {
            unset($data['data']['token']);
        }
        return $data;
    }

}