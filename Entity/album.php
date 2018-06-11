<?php

class album
{
    public $name;
    public $image;
    public $releaseDate;
    public $listeners;
    public $listOfTrack;

    public function setAlbum($name, $image, $releaseDate, $listeners, $listOfTrack){
        $this->name = $name;
        $this->image = $image;
        $this->releaseDate = $releaseDate;
        $this->listeners = $listeners;
        $this->listOfTrack = $listOfTrack;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): void
    {
        $this->image = $image;
    }

    public function getReleaseDate()
    {
        return $this->releaseDate;
    }

    public function setReleaseDate($releaseDate): void
    {
        $this->releaseDate = $releaseDate;
    }

    public function getListeners()
    {
        return $this->listeners;
    }

    public function setListeners($listeners): void
    {
        $this->listeners = $listeners;
    }

    public function getListOfTrack()
    {
        return $this->listOfTrack;
    }

    public function setListOfTrack($listOfTrack): void
    {
        $this->listOfTrack = $listOfTrack;
    }

}