<?php
/**
 * LAST.FM Chart to M3U Playlist Generator
 * Author Jon Borglund 2005 - 2009
 * LAST.FM API Key: XXX
 * LAST.FM SECRET:  XXX
 */
 class lastM3u
 {
    private $m3uContents;
    private $lastEntries = array();
    public function __construct($m3ufile)
    {
        $this->m3uContents = preg_grep('/^[^#]/',file($m3ufile));
    }

    public function getFound()
    {
        $return = array();
        foreach($this->lastEntries as $lastEntry) {
            if($lastEntry->getPlaylistEntry())
                $return[] = array(
                              'artist'   => $lastEntry->getArtist(),
                              'track'    => $lastEntry->getTrack(),
                              'playlist' => $lastEntry->getPlaylistEntry()
                            );
        }
        return $return;
    }

    public function getNotFound()
    {
        $return = array();
        foreach($this->lastEntries as $lastEntry) {
            if(!$lastEntry->getPlaylistEntry())
                $return[] = array(
                              'artist'   => $lastEntry->getArtist(),
                              'track'    => $lastEntry->getTrack(),
                            );
        }
        return $return;
    }

    public function load($name, $type)
    {
        $last = new lastXMLtoArray($name, $type);
        foreach($last->getArray() as $track) {
            $this->lastEntries[] = new lastEntry($track['artist'], $track['track']);
        }
        $this->findTracks();
    }

    private function findTracks()
    {
        $m = new lastMatch();
        $m->match($this->m3uContents, $this->lastEntries);
    }
 }
