<?php
require_once('./Model/accounts.php');
require_once('./Model/listTracks.php');

class controllerListTracks
{
    private $listTracks;
    private $accounts;

    public function __construct()
    {
        $this->listTracks = new listTracks();
        $this->accounts = new accounts();

        if (isset($_GET['checkListTracks'])) {
            $stateTrack = -1;
            if(!empty($_SESSION['login'])) {
                $account_id = $this->accounts->getIdAccount($_SESSION['login']);

                $stateTrack = $this->listTracks->getStateTrack($account_id, $_GET['href']);
            }

            echo json_encode(array('stateTrack'=>$stateTrack));
        }

        if (isset($_GET['deleteTrack'])) {
            if(!empty($_SESSION['login'])) {
                $account_id = $this->accounts->getIdAccount($_SESSION['login']);

                $deleteTrack = $this->listTracks->deleteTrack($account_id, $_GET['href']);

                echo json_encode(array('deleteTrack'=>$deleteTrack));
            }
        }
        if (isset($_GET['addTrack'])) {
            if(!empty($_SESSION['login'])) {
                $account_id = $this->accounts->getIdAccount($_SESSION['login']);

                $addTrack = $this->listTracks->addTrack($account_id, $_POST['href'], $_POST['name'], $_POST['artist'], $_POST['album']);

                echo json_encode(array('addTrack'=>$addTrack));
            }
        }

        if (isset($_GET['getListTracks'])) {
            if(!empty($_SESSION['login'])) {
                $account_id = $this->accounts->getIdAccount($_SESSION['login']);

                $getListTracks = $this->listTracks->getTracks($account_id);

                echo json_encode(array('listTracks'=>$getListTracks));
            }
        }

    }

}