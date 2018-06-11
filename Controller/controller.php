<?php

require_once('controllerMain.php');
require_once('controllerSearch.php');
require_once('controllerAccount.php');
require_once('controllerListTracks.php');

class controller
{
    public function __construct()
    {
        if(isset($_GET['search'])){
            new controllerSearch();
        }elseif(isset($_GET['account'])){
            new controllerAccount();
        }elseif(isset($_GET['listTrack'])){
            new controllerListTracks();
        }
        else{
            new controllerMain();
        }
    }
}
