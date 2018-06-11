<?php

require_once('./Entity/track.php');
require_once('./Entity/album.php');

class search
{
    public function getHTML($url){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://www.last.fm/'.$url);
        curl_setopt($curl,CURLOPT_USERAGENT,$_SERVER["HTTP_USER_AGENT"]);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);
        $resp = curl_exec($curl);
        curl_close($curl);
        return $resp;
    }

    public function getTracks($html){
        preg_match_all('/<span class=\"artist-name-spacer\">(.*?)href=\"(.*?)\"(.*?)title=\"(.*?)\"(.*?)class=\"chartlist-duration\">(.*?)<\/td>/s', $html, $tracks);

        $listOfTracks = [];

        for($i=0; $i<count($tracks[4]); $i++){
            $track = new track();
            $track->setHref($tracks[2][$i]);
            $track->setLength(trim($tracks[6][$i]));

            $exp = explode(' — ', $tracks[4][$i]);

            $track->setArtist($exp[0]);
            $track->setName($exp[1]);

            $listOfTracks[] = $track;
        }

        return $listOfTracks;
    }

    public function getTrackInfo($html){
        preg_match_all('/data-page-resource-name=\"(.*?)\"(.*?)data-page-resource-artist-name=\"(.*?)\"/s', $html, $info);
        $name = $info[1][0];
        $artist = $info[3][0];

        preg_match_all('/header-title-duration\">\((.*?)\)<\/span>/s', $html, $info);
        if(!empty($info[1][0])) {
            $length = $info[1][0];
        }
        else{
            $length = '';
        }

        preg_match_all('/<h4 class=\"header-metadata-title\">Listeners<\/h4>(.*?)class=\"header-metadata-display\"(.*?)class=\"intabbr\" title=\"(.*?)\">(.*?)<\/abbr>/s', $html, $info);
        $listeners = $info[4][0];

        preg_match_all('/<link itemprop=\"url\" href=\"(.*?)\" \/>/s', $html, $info);
        $href = $info[1][0];

        preg_match_all('/<div class=\"video-preview-playlink\">(.*?)data-youtube-url=\"(.*?)\"/s', $html, $info);
        $youtube = '';
        if(isset($info[2][0]))
        {
            $youtube = $info[2][0];
        }

        $htmlDescription = $this->getHTML($href.'/+wiki');
        preg_match_all('/<div class=\"wiki-content\" itemprop=\"description\">(.*?)<\/div>/s', $htmlDescription, $info);
        $description = '';
        if(isset($info[1][0]))
        {
            $description = $info[1][0];
        }

        $track = new track();
        $track->setTrack($name, $artist, $length, $href, $listeners, $description, $youtube, '_');

        preg_match_all('/<h3 class=\"featured-item-name\" itemprop=\"name\"><a href=\"(.*?)\"/s', $html, $info);
        if(!empty($info[1][0])){
            $albumUrl = $info[1][0];
            $htmlAlbum = $this->getHTML($albumUrl);

            $track = new track();
            $track->setTrack($name, $artist, $length, $href, $listeners, $description, $youtube, $albumUrl);


            preg_match_all('/<h1 class=\"header-title\" itemprop=\"name\">(.*?)<\/h1>/s', $htmlAlbum, $info);
            $albumName = trim($info[1][0]);

            preg_match_all('/class=\"cover-art\">(.*?)<img(.*?)src=\"(.*?)\"(.*?)alt=\"(.*?)\"(.*?)itemprop=\"image\"(.*?)class=\"cover-art\"/s', $htmlAlbum, $info);
            $albumImage = $info[3][0];

            preg_match_all('/<h2 class=\"metadata-title\">Release date<\/h2>(.*?)<p class=\"metadata-display\">(.*?)<\/p>/s', $htmlAlbum, $info);
            if(!empty($info[2][0])){
                $releaseDate = $info[2][0];
            }else{
                $releaseDate = 'Brak danych';
            }

            preg_match_all('/<h4 class=\"header-metadata-title\">Listeners<\/h4>(.*?)<abbr class=\"intabbr\" title=\"(.*?)\">(.*?)<\/abbr>/s', $htmlAlbum, $info);
            $albumListeners = $info[3][0];
            preg_match_all('/<span class=\"chartlist-ellipsis-wrap\">(.*?)href=\"(.*?)\"(.*?)class=\"link-block-target\"(.*?)>(.*?)<\/a>(.*?)<td class=\"chartlist-duration\">(.*?)<\/td>/s', $htmlAlbum, $info);

            $listOfAlbumTracks = [];
            for($i=0; $i<count($info[2]); $i++){
                $albumTrackUrl = $info[2][$i];
                $albumTrackName = $info[5][$i];
                $albumTrackLength = $info[7][$i];

                $albumTrack = new track();
                $albumTrack->setHref($albumTrackUrl);
                $albumTrack->setLength(trim($albumTrackLength));
                $albumTrack->setName($albumTrackName);

                $listOfAlbumTracks[] = $albumTrack;
            }

            $album = new album();
            $album->setAlbum($albumName, $albumImage, $releaseDate, $albumListeners, $listOfAlbumTracks);
        }
        else{
            $album = new album();
            $album->setAlbum('Brak albumu', '', 'Brak daty wydania', 'Brak słuchaczy', $track);
        }




        return array("track" => $track, "album" => $album);
    }

    public function convertUrl($url){
        $url = str_replace(['?', ',', '\'', ';', ':', '[', ']', '{', '}', '\\', '|', '@', '#', '$', '%', '^', '&', '=',  '`'],
            ['%3F', '%2C', '%27', '%3B', '%3A', '%5B', '%5D', '%7B', '%7D', '%5C', '%7C', '%40', '%23', '%24', '%25', '%5E', '%26', '%3D',  '%60'],
            $url);
        $url = str_replace(' ', '+', $url);

        preg_match_all('/music\/(.*?)\/_\/(.*?)$/s', $url, $info);
        $first = $info[1][0];
        $firstChanged = str_replace('/', '%2F', $first);
        $second = $info[2][0];
        $secondChanged = str_replace('/', '%2F', $second);

        $url = str_replace($first, $firstChanged, $url);
        $url = str_replace($second, $secondChanged, $url);

        return $url;
    }

}