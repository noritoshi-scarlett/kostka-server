<?php

namespace App\Controllers;

use App\Models\SubscribeModel;

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
        $userID = $this->session->get('user_id');

        if ($this->logged) {
            $forumsList = (object) [
                        'subscribed'   => $this->subscribeList,
                        'unSubscribed' => $this->libraryModel->getUnSubscribedForumsByUser($userID),
            ];
        } else {
            $forumsList = (object) [
                        'all' => $this->libraryModel->where('visibility', true)->get()->getResult()
            ];
        }
        
        
        $addItem = function(object $forum, ?bool $addButton) {
            $returnHtml = "<div class='two-thirds column'>
                    $forum->name<br>
                    <a title='$forum->name' href='$forum->domain'>$forum->domain</a>
                </div>";
            if (!is_null($addButton)) {
                $icon = $addButton ? 'fa-plus' : 'fa-minus';
                $buttonClass = $addButton ? 'subscribe-button' : 'unsubscribe-button';
                $returnHtml .= "<div class='one-third column'>
                        <button type='button' class='button button-image $buttonClass' data-forum-id='$forum->id'>
                            <i class='fa fa-2x $icon'></i>
                        </button>
                    </div>";
            }
            return str_replace("'", '"', $returnHtml);
        };

        $this->showPage('library_index',
                [
                    'forumsList' => $forumsList,
                    'addItem'    => $addItem,
                ]
        );
    }

    /**
     * Subscribe given forum.
     *
     * @param int $forumID  Forum ID.
     *
     * @return void
     */
    public function subscribeForum(int $forumID): string
    {
        if (!$this->logged || !$this->request->isAJAX()) {
            return json_encode([
                'result' => 0,
                'access' => 'forbidden'
            ]);
        }
        $userID = $this->session->get('user_id');
        $subscribeModel = new SubscribeModel();
        $result = $subscribeModel->changeSubscribtion(true, $userID, $forumID);
        return json_encode([
            'result' => (int) $result
        ]);
    }

    /**
     * Unsubscribe given forum.
     *
     * @param int $forumID  Forum ID.
     *
     * @return void
     */
    public function unSubscribeForum(int $forumID): string
    {
        if (!$this->logged || !$this->request->isAJAX()) {
            return json_encode([
                'result' => 0,
                'access' => 'forbidden'
            ]);
        }
        $userID = $this->session->get('user_id');
        $subscribeModel = new SubscribeModel();
        $result = $subscribeModel->changeSubscribtion(false, $userID, $forumID);
        return json_encode([
            'result' => (int) $result
        ]);
    }
}
