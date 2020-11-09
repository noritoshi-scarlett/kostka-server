<?php

namespace App\Models;

use CodeIgniter\Model;

class RollModel extends Model
{

    protected $table = 'dice_rolls';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'user_id',
        'forum_id',
        'hash',
        'meta_data',
        'title'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];
    protected $validationRules = [
        'hash'      => 'required|min_length[3]|is_unique[dice_rolls.hash]|alpha_numeric',
        'meta_data' => 'required|min_length[5]',
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;

    /**
     * Actions before insert data.
     *
     * @param array $data Data for insert.
     *
     * @return type
     */
    protected function beforeInsert(array $data)
    {
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
        return $data;
    }

}
