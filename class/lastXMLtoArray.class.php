<?php
/**
 * LAST.FM Chart to M3U Playlist Generator
 * Author Jon Borglund 2005 - 2009
 * LAST.FM API Key: XXX
 * LAST.FM SECRET:  XXX
 */
 class lastXMLtoArray
 {
    const TYPE_USERLOVED   = 1;
    const TYPE_USERWEEKLY  = 2;
    const TYPE_GROUPWEEKLY = 3;
    const TYPE_TAGTOP      = 4;
    private $urlSprint = 'http://ws.audioscrobbler.com/2.0/?method=%s%s&api_key=XXX';
    private $xml;
    private $simpleXml;
    private $array = array();
    private $url;
    private $name;
    public function __construct($name ,$type = false)
    {
        $this->name = $name;
        switch($type) {
            case lastXMLtoArray::TYPE_USERLOVED:
                $this->loadUserLoved();
            break;
            case lastXMLtoArray::TYPE_USERWEEKLY:
                $this->loadUserWeekly();
            break;
            case lastXMLtoArray::TYPE_GROUPWEEKLY:
                $this->loadGroupWeekly();
            break;
            case lastXMLtoArray::TYPE_TAGTOP:
                $this->loadTagTop();
            break;
            default:
                $this->loadUserTop();
            break;
        }
    }

    public function getArray()
    {
        return $this->array;
    }

    public function getXml()
    {
        return $this->xml;
    }

    public function getUrl()
    {
        return $this->url;
    }

    private function loadUserWeekly()
    {
        $this->loadXML('user.getweeklytrackchart&user=');
        $this->iterateXml($this->simpleXml->weeklytrackchart);
    }

    private function loadUserLoved()
    {
        $this->loadXML('user.getlovedtracks&user=');
        $this->iterateXml($this->simpleXml->lovedtracks);
    }

    private function loadUserTop()
    {
        $this->loadXML('user.gettoptracks&user=');
        $this->iterateXml($this->simpleXml->toptracks);
    }

    private function loadGroupWeekly()
    {
        $this->loadXML('group.getweeklytrackchart&group=');
        $this->iterateXml($this->simpleXml->weeklytrackchart);
    }

    private function loadTagTop()
    {
        $this->loadXML('tag.gettoptracks&tag=');
        $this->iterateXml($this->simpleXml->toptracks);
    }

    private function loadXML($urlPart)
    {
        $this->url = sprintf($this->urlSprint,$urlPart,str_replace(' ','+',trim($this->name)));
        if (!$this->xml = @file_get_contents($this->url)) {
            throw new Exception('Unable to fetch xml from last.fm');
        }
        $this->simpleXml = simplexml_load_string($this->xml);
    }

    private function iterateXml($startNode)
    {
        $this->array = array();
        foreach($startNode->track as $track) {
            $this->array[] = array(
              'artist' => ((string) $track->artist->name[0] == '') ? (string) $track->artist : (string) $track->artist->name[0],
              'track'  => (string) $track->name[0]
            );
        }
    }
}
