<?php
require_once('./Entity/listTrack.php');
require_once('./Model/db_connect.php');
require_once('./Model/search.php');

class listTracks
{
    private $db;
    private $search;

    public function __construct()
    {
        $this->db = new db_connect();
        $this->search = new search();
    }

    public function getStateTrack($account_id, $href){
        $href = $this->search->convertUrl($href);

        $query = "SELECT COUNT(id) AS num FROM listTracks WHERE account_id='".$account_id."' AND href='".$href."';";
        $query = $this->db->getQuery($query);
        $result = $query->fetch(PDO::FETCH_BOTH);
        if($result['num'] == 0)
            return 0;

        return 1;
    }


    public function deleteTrack($account_id, $href){
        $href = $this->search->convertUrl($href);
        $query = "DELETE FROM listTracks WHERE account_id='".$account_id."' AND href='".$href."'";
        $this->db->getQuery($query);
        return 1;
    }

    public function addTrack($account_id, $href, $name, $artist, $album){
        $href = $this->search->convertUrl($href);

        $query = "INSERT INTO listTracks(id, account_id, href, name, artist, album) VALUES('', :account_id, :href, :name, :artist, :album);";
        $stmt = $this->db->prepareQuery($query);
        $stmt->bindParam(':account_id', $account_id);
        $stmt->bindParam(':href', $href);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':artist', $artist);
        $stmt->bindParam(':album', $album);
        $stmt->execute();

        return 1;
    }

    public function getTracks($account_id){
        $query = "SELECT * FROM listTracks WHERE account_id='".$account_id."';";
        $query = $this->db->getQuery($query);
        $listTracks = [];
        while ($result = $query->fetch(PDO::FETCH_BOTH)) {
            $listTrack = new listTrack();
            $listTrack->setListTrack($result['id'], $result['account_id'], $result['href'], $result['name'],
                $result['artist'], $result['album']);
            $listTracks[] = $listTrack;
        }

        return $listTracks;
    }



}