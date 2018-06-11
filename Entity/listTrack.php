<?php

class listTrack
{
    public $id;
    public $account_id;
    public $href;
    public $name;
    public $artist;
    public $album;

    public function setListTrack($id, $account_id, $href, $name, $artist, $album){
        $this->id = $id;
        $this->account_id = $account_id;
        $this->href = $href;
        $this->name = $name;
        $this->artist = $artist;
        $this->album = $album;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getAccountId()
    {
        return $this->account_id;
    }

    public function setAccountId($account_id): void
    {
        $this->account_id = $account_id;
    }

    public function getHref()
    {
        return $this->href;
    }

    public function setHref($href): void
    {
        $this->href = $href;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getArtist()
    {
        return $this->artist;
    }

    public function setArtist($artist): void
    {
        $this->artist = $artist;
    }

    public function getAlbum()
    {
        return $this->album;
    }

    public function setAlbum($album): void
    {
        $this->album = $album;
    }


}