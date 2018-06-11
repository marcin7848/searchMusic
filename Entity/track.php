<?php

class track
{
    public $name;
    public $artist;
    public $length;
    public $href;
    public $listeners;
    public $description;
    public $youtube;
    public $albumUrl;

    public function setTrack($name, $artist, $length, $href, $listeners, $description, $youtube, $albumUrl){
        $this->name = $name;
        $this->artist = $artist;
        $this->length = $length;
        $this->href = $href;
        $this->listeners = $listeners;
        $this->description = $description;
        $this->youtube = $youtube;
        $this->albumUrl = $albumUrl;
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

    public function getLength()
    {
        return $this->length;
    }

    public function setLength($length): void
    {
        $this->length = $length;
    }

    public function getHref()
    {
        return $this->href;
    }

    public function setHref($href): void
    {
        $this->href = $href;
    }

    public function getListeners()
    {
        return $this->listeners;
    }

    public function setListeners($listeners): void
    {
        $this->listeners = $listeners;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description): void
    {
        $this->description = $description;
    }

    public function getYoutube()
    {
        return $this->youtube;
    }

    public function setYoutube($youtube): void
    {
        $this->youtube = $youtube;
    }

    public function getAlbumUrl()
    {
        return $this->albumUrl;
    }

    public function setAlbumUrl($albumUrl): void
    {
        $this->albumUrl = $albumUrl;
    }


}