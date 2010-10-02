<?php
/**
 * LAST.FM Chart to M3U Playlist Generator
 * Author Jon Borglund 2005 - 2009
 * LAST.FM API Key: XXX
 * LAST.FM SECRET:  XXX
 */
 class lastMatch
 {
    private $dbh;

    public function __construct() {
        $dbh = new PDO('sqlite::memory:');
        $dbh->exec("CREATE TABLE playlist (id INTEGER PRIMARY KEY ASC, file TEXT)");
        $dbh->exec("CREATE TABLE match (id INTEGER PRIMARY KEY ASC, artist TEXT, track TEXT, match TEXT)");
        $this->dbh = $dbh;
    }

    public function match($playlistArray, $lstfmEntries)
    {
        $sth = $this->dbh->prepare("INSERT INTO playlist (file) VALUES (:file)");
        $sth->bindParam(':file', $file, PDO::PARAM_STR);
        foreach($playlistArray as $file) {
            $sth->execute();
        }

        $sth = $this->dbh->prepare("SELECT `file` FROM `playlist` WHERE `file` LIKE :match");
        $sth->bindParam(':match',  $match,  PDO::PARAM_STR);
        foreach($lstfmEntries as $lstEntry) {
            $match = $this->cleanStringToLike($lstEntry->getArtist() . ' ' . $lstEntry->getTrack());
            if(!preg_match('#^%+$#', $match)) {
                $sth->execute();
                foreach($sth->fetchAll(PDO::FETCH_ASSOC) as $file) {
                    $lstEntry->setPlayListEntry($file['file']);
                }
            }
        }
    }

    private function cleanStringToLike($str)
    {
        return '%' . preg_replace('#[^A-Za-z\d]#','%',$str) . '%';
    }

 }
