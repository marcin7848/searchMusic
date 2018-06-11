<?php

require_once('./Model/search.php');

class controllerSearch
{
    private $search;

    public function __construct(){
        $this->search = new search();

        if(isset($_GET['tracks'])){
            $url = str_replace(' ', '+', $_GET['tracks']);
            $html = $this->search->getHTML('search?q='.$url);

            $listOfTracks = $this->search->getTracks($html);

            echo json_encode($listOfTracks);

        }

        if(isset($_GET['trackInfo'])){
            $url = $this->search->convertUrl($_GET['trackInfo']);

            $html = $this->search->getHTML($url);

            $track = $this->search->getTrackInfo($html);

            echo json_encode($track);

        }

    }
}