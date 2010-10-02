<?php
/**
 * LAST.FM Chart to M3U Playlist Generator
 * Author Jon Borglund 2005 - 2009
 * LAST.FM API Key: XXX
 * LAST.FM SECRET:  XXX
 */
 class lastEntry
 {
    private $lastArtist;
    private $lastTrack;
    private $playlistEntry = array();

    public function __construct($artist, $track)
    {
        $this->lastArtist = $artist;
        $this->lastTrack = $track;
    }

    public function setPlaylistEntry($val)
    {
        $this->playlistEntry[] = $val;
    }

    public function getPlaylistEntry()
    {
        return $this->playlistEntry;
    }

    public function getArtist()
    {
        return $this->lastArtist;
    }

    public function getTrack()
    {
        return $this->lastTrack;
    }

 }
