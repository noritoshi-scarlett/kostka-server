<?php

namespace App\Controllers;

use App\Models\RollModel;
use App\Models\DiceModel;
use Endroid\QrCode\QrCode;
use function redirect;

/**
 * Dice Controller.
 */
class Dice extends \App\Controllers\BaseController
{

    /**
     * Display main page for dice rolling.
     *
     * @param string $forumCodeName Forum code name.
     * @param string $rollingHash   Rolling hash (generated in Dice::generateQrCode)
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function rolls(string $forumCodeName, string $rollingHash)
    {
        $forumData = $this->libraryModel->select('id, icon, code_name, name')
                ->where('code_name', $forumCodeName)
                ->get(1)
                ->getFirstRow();
        if (!isset($forumData)) {
            return redirect()->to('/library');
        }
        // Check if exist
        $rollingModel = new RollModel();
        $rollData = $rollingModel->select('id')
                ->where('forum_id', $forumData->id)
                ->where('hash', $rollingHash)
                ->get(1)
                ->getFirstRow();
        if (!isset($rollData)) {
            return redirect()->to("/library/$forumCodeName");
        }
        $diceModel = new DiceModel();
        $diceConfiguration = (object) [
            'types' => $diceModel->getActiveDiceTypes($forumData->id)
        ];

        $this->showPage('dice_roll', [
            'forum'         => $forumData,
            'config'        => $diceConfiguration,
            'foreignAuthor' => false,
            'diceDoubled'   => false,
            'invalidData'   => false,
            'roll'          => (object) [
                'qrImage' => $this->showQrCode($forumCodeName, $rollingHash,
                        'link'),
                'code'    => '',
                'url'     => ''
            ],
            'dices'         => []
        ]);
    }

    /**
     * Generate Qr code for given data and return token.
     *
     * @param string $forumCodeName Forum code name.
     * @param string $token         Special token for forum.
     *
     * @return string Token for Qr code.
     */
    public function generateQrCode(string $forumCodeName, string $token)
    {
        $forumData = $this->libraryModel->select('id')
                ->where('code_name', $forumCodeName)
                ->where('token', $token)
                ->get(1)
                ->getFirstRow();
        if (!isset($forumData)) {
            $this->response
                    ->setStatusCode(404)
                    ->setBody(null)
                    ->send();
            return;
        }

        $rollingModel = new RollModel();
        $metaData = $this->request->getPost('meta_data');
        $decodedMetaData = json_encode([
            'topic'    => (int) $metaData['topic'],
            'forum'    => (int) $metaData['forum'],
            'username' => (string) $metaData['username']
        ]);
        $title = $this->request->getPost('title') ?? '';

        // Check if exist
        $rollData = $rollingModel->select('id, hash')
                ->where('forum_id', $forumData->id)
                ->where('title', $title)
                ->where('meta_data', $decodedMetaData)
                ->get(1)
                ->getFirstRow();
        if (!isset($rollData)) {
            //add rolling to table
            $rollingId = $rollingModel->insert([
                'user_id'   => $this->session->get('user_id') ?? null,
                'forum_id'  => $forumData->id,
                'hash'      => bin2hex(\CodeIgniter\Encryption\Encryption::createKey(32)),
                'title'     => $title,
                'meta_data' => $decodedMetaData,
            ]);
            if (false === $rollingId) {
                $this->response
                    ->setStatusCode(404)
                    ->setContentType('json')
                    ->setJSON([
                        'error'   => true,
                        'message' => 'cannot add dice roll'
                    ])
                    ->send();
                return;
            }
            $rollData = $rollingModel->find($rollingId);
            if (null === $rollData) {
                $this->response
                    ->setStatusCode(404)
                    ->setContentType('json')
                    ->setJSON([
                        'error'   => true,
                        'message' => 'cannot find added record'
                    ])
                    ->send();
                return;
            }
        }

        $type = $this->request->isAJAX() ? 'json' : 'img';
        $this->showQrCode($forumCodeName, $rollData, $type);
    }

    /**
     * Generate Qr code for given data and return token.
     *
     * @param string $forumCodeName Forum code name.
     * @param string $rollData      Rolling hash.
     * @param string $type          Type of return data: json|image|link
     *
     * @return void
     */
    private function showQrCode(string $forumCodeName, string $rollData, string $type): void
    {
        $link = base_url("/dice/rolls/$forumCodeName/{$rollData->hash}/");
        //$link = "https://postarium.pl/dice/rolls/$forumCodeName/{$rollData->hash}/";
        $qrCode = new QrCode($link);

        switch ($type) {
            case 'json':
                $imageResponse = $qrCode->writeDataUri();
                $this->response
                    ->setStatusCode(200)
                    ->setContentType('json')
                    ->setJSON([
                        'error' => false,
                        'link'  => $link,
                        'image' => $imageResponse
                    ])
                    ->send();
                return;

            case 'image':
                $this->response
                    ->setStatusCode(200)
                    ->setContentType($qrCode->getContentType())
                    ->setBody($qrCode->writeString())
                    ->send();

            case 'link':
                
        }
    }

}