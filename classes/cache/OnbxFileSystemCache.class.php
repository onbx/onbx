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
 * Based on onPHP's RubberFileSystem class by Anton E. Lebedevich
 * and Konstantin V. Arkhipov.
 * 
 * Warning: implementation is not thread-safe, so corruptions are possible.
 *
 * @since 2010-03-17
 */
onbxImport('OnbxFileUtils');

class OnbxFileSystemCache {
    const TIME_SWITCH = 2592000; // 60 * 60 * 24 * 30

    private $directory	= null;

    public static function create($directory) {
        return new self($directory);
    }

    public function __construct($directory) {
        if (!is_writable($directory)) {
            if (!mkdir($directory, 0700, true)) {
                throw new WrongArgumentException(
                    "can not write to '{$directory}'"
                );
            }
        }

        if ($directory[strlen($directory) - 1] != DIRECTORY_SEPARATOR)
            $directory .= DIRECTORY_SEPARATOR;

        $this->directory = $directory;
    }

    public function clean() {
        OnbxFileUtils::removeDirectory($this->directory, true);
    }

    public function get($key) {
        $path = $this->makePath($key);

        if (is_readable($path)) {
            if (filemtime($path) <= time()) {
                try {
                    unlink($path);
                } catch (Exception $e) {
                    // we're in race with unexpected clean()
                }
                return null;
            }
            return $this->operate($path);
        }
        return null;
    }

    public function delete($key) {
        try {
            unlink($this->makePath($key));
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    public function set($key, $value, $expires) {
        return $this->store('set', $key, $value, $expires);
    }


    private function store($action, $key, $value, $expires) {
        $path = $this->makePath($key);
        $time = time();

        $directory = dirname($path);

        if (!file_exists($directory)) {
            try {
                mkdir($directory);
            } catch (Exception $e) {
                // we're in race
            }
        }

        $this->operate($path, $value, $expires);

        return true;
    }

    private function operate($path, $value = null, $expires = null) {
        try {
            $fp = fopen($path, $value !== null ? 'wb' : 'rb');
        } catch (Exception $e) {
            return null;
        }

        if ($value !== null) {
            fwrite($fp, $this->prepareData($value));
            fclose($fp);

            if ($expires < self::TIME_SWITCH) {
                $expires += time();
            }

            try {
                touch($path, $expires);
            } catch (Exception $e) {
                // race-removed
            }
            return null;
        } else {
            if (($size = filesize($path)) > 0) {
                $data = fread($fp, $size);
            } else {
                $data = null;
            }

            fclose($fp);

            return $data ? $this->restoreData($data) : null;
        }
    }

    private function prepareData($value) {
        return serialize($value);
    }

    private function restoreData($value) {
        return unserialize($value);
    }

    private function makePath($key) {
        $realKey = md5($key);
        return
            $this->directory
            .$realKey[0].$realKey[1]
            .DIRECTORY_SEPARATOR
            .substr($realKey, 2);
    }
}
?>
