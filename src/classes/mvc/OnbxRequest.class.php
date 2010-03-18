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
 * @since 2010-03-11
 */
onbxImport('OnbxMap');
onbxImport('OnbxHttpUrl');

class OnbxRequest {
    private $attributes;
    private $getVars;
    private $postVars;
    private $serverVars;

    public static function create() {
        return new self;
    }

    public function __construct() {
        $this->attributes = OnbxMap::create();
        $this->serverVars = OnbxMap::create();
        $this->getVars = OnbxMap::create();
        $this->postVars = OnbxMap::create();
    }

    public function setAttributes(OnbxMap $attributes) {
        $this->attributes = $attributes;
        return $this;
    }

    public function getAttributes() {
        return $this->attributes;
    }

    public function hasAttribute($name) {
        return $this->attributes->containsKey($name);
    }

    public function getAttribute($name) {
        return $this->attributes->get($name);
    }

    public function setServerVars(OnbxMap $serverVars) {
        $this->serverVars = $serverVars;
        return $this;
    }

    public function getServerVars() {
        return $this->serverVars;
    }

    public function hasServerVar($name) {
        return $this->serverVars->containsKey($name);
    }

    public function getServerVar($name) {
        return $this->serverVars->get($name);
    }

    public function setGetVars(OnbxMap $getVars) {
        $this->getVars = $getVars;
        return $this;
    }

    public function getGetVars() {
        return $this->getVars;
    }

    public function setPostVars(OnbxMap $postVars) {
        $this->postVars = $postVars;
        return $this;
    }

    public function getPostVars() {
        return $this->postVars;
    }

    public function getUrl() {
        $result = new OnbxHttpUrl();
        if ($this->hasServerVar('HTTP_HOST') || $this->hasServerVar('SERVER_NAME')) {
            $result->
                setScheme($this->hasServerVar('HTTPS') ? 'https' : 'http')->
                setHost(
                    $this->hasServerVar('SERVER_NAME')
                    ? $this->getServerVar('SERVER_NAME')
                    : $this->hasServerVar('HTTP_HOST')
                );
        }

        if ($this->hasServerVar('REQUEST_URI')) {
            $path = $this->getServerVar('REQUEST_URI');
            $pos = strpos($path, '?');

            if ($pos !== false)
                $path = substr($path, 0, $pos);

            $result->setPath($path);
        }

        if (!$this->getVars->isEmpty())
            $result->setQuery(
                http_build_query($this->getVars->toNativeArray()));

        return $result;
    }

    public function __toString() {
        return sprintf("%s[url: %s][attributes: %s]",
            get_class($this),
            var_export($this->attributes, true),
            $this->getUrl());
    }
}
?>
