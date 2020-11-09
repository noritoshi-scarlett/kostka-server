<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ForumModel;

/**
 * Forum Controller.
 */
class Forum extends BaseController
{

    /**
     * Display index page.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function view(string $forumCodeName)
    {
        if (!$this->logged) {
            return redirect()->to('/library');
        }
        $userModel = new UserModel();
        $forumData = $this->libraryModel->select('id, icon, code_name, name, description')
                ->where('code_name', $forumCodeName)
                ->get(1)
                ->getFirstRow();
        if (!isset($forumData)) {
            return redirect()->to('/library');
        }

        $this->showPage('forum_view', [
            'forum'      => $forumData,
            'forumUsers' => [],
            'forumRolls' => []
        ]);
    }
}