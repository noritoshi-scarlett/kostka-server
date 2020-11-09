<?php

namespace App\Models;

use CodeIgniter\Model;

class DiceModel extends Model
{

    protected $table = 'dices';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'code_name',
        'image',
        'cube_size',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = null;
    protected $beforeInsert = [];
    protected $beforeUpdate = [];
    protected $validationRules = [
        'code_name' => 'required|min_length[1]|is_unique[forums.code_name]|alpha_numeric_space',
        'image'     => 'required|min_length[1]',
        'cube_size' => 'required|min_length[1]|is_number',
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;

    /**
     * Get availible dice types for given forum.
     *
     * @param int $forumID Forum id.
     *
     * @return array
     */
    public function getActiveDiceTypes(int $forumID): array
    {
        return $this->builder()
                ->select('dices.*')
                ->join('forums_dices', 'dices.id = forums_dices.dice_id')
                ->where('forums_dices.forum_id', $forumID)
                ->get()
                ->getResult();
    }
}