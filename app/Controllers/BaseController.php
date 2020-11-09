<?php

namespace App\Controllers;

use App\Models\LibraryModel;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 *
 * @package CodeIgniter
 */
use CodeIgniter\Controller;

class BaseController extends Controller
{

    private const ACTIVE_INTERVAL = 300; // 5 minutes.

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [];

    /**
     * Session handler.
     *
     * @var \CodeIgniter\Session\Session
     */
    protected $session;

    /**
     * Current user is logged.
     *
     * @var bool
     */
    protected $logged;

    /**
     * List of subscribed forums.
     *
     * @var array 
     */
    protected $subscribeList;

    /**
     * Library Model instance.
     *
     * @var LibraryModel
     */
    protected $libraryModel;

    /**
     * Incoming data.
     *
     * @var \CodeIgniter\HTTP\RequestInterface
     */
    protected $request;

    /**
     * Constructor.
     */
    public function initController(
            \CodeIgniter\HTTP\RequestInterface $request,
            \CodeIgniter\HTTP\ResponseInterface $response,
            \Psr\Log\LoggerInterface $logger
    )
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        $this->session = \Config\Services::session();
        $this->logged = !empty($this->session->get('user_id'));
        
        //request
        $this->request = $request;

        // Last activity
        $userID = $this->session->get('user_id');
        if (time() - $this->session->get('last_activity') >= self::ACTIVE_INTERVAL) {
            $userModel = new \App\Models\UserModel();
            $userModel->update($userID, ['last_activity' => time()]);
        }

        //Subscibed list
        $this->libraryModel = new LibraryModel();
        if ($this->logged) {
            $this->subscribeList = $this->libraryModel->getSubscribedForumsByUser($userID);
        }
    }

    /**
     * Show page by given name and with given data.
     *
     * @param string $pageName Template name (for file).
     * @param array  $data     Data (languages, user data, data for show etc.).
     *
     * @return void
     */
    protected function showPage(string $pageName, array $data): void
    {
        $data['user'] = (object) [
                    'logged'          => $this->logged,
                    'userId'          => $this->session->get('user_id'),
                    'username'        => $this->session->get('username'),
                    'permissionLevel' => $this->session->get('permission_level')
        ];
        $data['subscribeList'] = $this->subscribeList;
        
        echo view('header.html', $data)
        . view('nav.html', $data)
        . view("$pageName.html", $data)
        . view('footer.html', $data);
    }

}
