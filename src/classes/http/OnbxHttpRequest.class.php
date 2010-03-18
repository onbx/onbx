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

class OnbxHttpRequest {
    private $method; // TODO: HttpMethod
    private $url; // TODO: HttpUrl
    private $headers;

    public function __construct($method, $url) {
        $this->method = $method;
        $this->url = $url;
        $this->headers = OnbxMap::create();
    }

    public function setHeaders(OnbxMap $headers) {
        $this->headers = $headers;
        return $this;
    }

    public function getHeaders() {
        return $this->headers;
    }

    public function getUrl() {
        return $this->url;
    }

    public function getMethod() {
        return $this->method;
    }
}
?>
