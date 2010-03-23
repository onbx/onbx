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
class OnbxMap {
    private $map;

    public static function create(array $map = array()) {
        return new self($map);
    }

    public function __construct(array $map = array()) {
        $this->map = $map;
    }

    public function containsKey($name) {
        return array_key_exists($name, $this->map);
    }

    public function put($name, $value) {
        $this->map[$name] = $value;
        return $this;
    }

    public function get($name) {
        if (!$this->containsKey($name)) {
            throw new OnbxIllegalArgumentException('i know nothing about attribute '.$name);
        }
        return $this->map[$name];
    }

    public function remove($name) {
        $this->get($name); // just check for existence
        unset($this->map[$name]);
    }

    public function isEmpty() {
        return count($this->map) == 0;
    }

    public function toNativeArray() {
        return $this->map;
    }

    public function __toString() {
        $result = get_class($this).'[';
        foreach ($this->map as $key => $value) {
            $result .= "[$key => $value]";
        }
        $result .= ']';
        return $result;
    }
}
?>
