<?php

namespace App\Models;

use CodeIgniter\Model;

class SubscribeModel extends Model
{

    protected $table = 'users_forums';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'user_id',
        'forum_id'
    ];
    protected $useTimestamps = true;
    protected $createdField = null;
    protected $updatedField = null;
    protected $beforeInsert = null;
    protected $beforeUpdate = null;
    protected $validationMessages = [];
    protected $skipValidation = false;

    /**
     * Change forum subscribtion wih given data.
     *
     * @param bool $addSubscribtion True for subscribe, false for unsubscribe.
     * @param int $userID           User ID.
     * @param int $forumID          Forum ID.
     *
     * @return bool
     */
    public function changeSubscribtion(
            bool $addSubscribtion, int $userID, int $forumID
    ): ?bool
    {
        $dataArray = [
            'user_id'  => $userID,
            'forum_id' => $forumID
        ];

        $isSubscribed = $this->builder()
                ->getWhere($dataArray)
                ->getFirstRow();

        // Subscibe.
        if ($addSubscribtion) {
            if (empty($isSubscribed)) {
                return is_int($this->insert($dataArray));
            }
            return true;
        }
        // Unsubscribe.
        if (empty($isSubscribed)) {
            return true;
        }
        return is_int($this->delete($isSubscribed->id)->connID->affected_rows);
    }
}
