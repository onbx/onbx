<?php
/*
  Copyright (C) 2010 by Ivan Y. Khvostishkov 
 
  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.
 
  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.
 
  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * @since 2010-03-12
 */
onbxImport('OnbxMap');

class OnbxHttpUrl {
    private $scheme;
    private $host;
    private $port;
    private $path;
    private $query;

    public static function parse($url) {
        $result = new self;

        $parts = OnbxMap::create(parse_url($url));

        $result->setScheme($parts->get('scheme'));
        $result->setHost($parts->get('host'));

        if ($parts->containsKey('port')) {
            $result->setPort($parts->get('port'));
        }

        $result->setPath($parts->get('path'));

        if ($parts->containsKey('query')) {
            $result->setQuery($parts->get('query'));
        }

        return $result;
    }

    public function setScheme($scheme) {
        $this->scheme = $scheme;
        return $this;
    }

    public function getScheme() {
        return $this->scheme;
    }

    public function setHost($host) {
        $this->host = $host;
        return $this;
    }

    public function getHost() {
        return $this->host;
    }

    public function setPort($port) {
        $this->port = $port;
        return $this;
    }

    public function getPort() {
        return $this->port;
    }
    
    public function setPath($path) {
        $this->path = $path;
        return $this;
    }

    public function getPath() {
        return $this->path;
    }
    
    public function setQuery($query) {
        $this->query = $query;
        return $this;
    }

    public function getQuery() {
        return $this->query;
    }

    public function getAuthority() {
        $result = null;
        
        // TODO:
        /*
        if ($this->userInfo !== null)
            $result .= $this->userInfo.'@';
         */

        if ($this->host !== null)
            $result .= $this->host;

        if ($this->port !== null)
            $result .= ':'.$this->port;

        return $result;
    }

    public function getSchemeSpecificPart() {
        $result = null;

        $authority = $this->getAuthority();

        if ($authority !== null)
            $result .= '//'.$authority;

        $result .= $this->path;

        if ($this->query !== null)
            $result .= '?'.$this->query;

        // TODO:
        /*
        if ($this->fragment !== null)
            $result .= '#'.$this->fragment;
         */

        return $result;
    }

    public function __toString() {
        $result = null;

        if ($this->scheme !== null)
            $result .= $this->scheme.':';

        $result .= $this->getSchemeSpecificPart();

        return $result;
    }
}
?>
