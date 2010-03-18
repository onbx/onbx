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
class OnbxDirectoryContext {
    private static $directoryStack = array();

    public static function spawnDir($path) {
        if (!is_dir($path)) {
            throw new OnbxIllegalArgumentException('directory does not exists: ' + $path);
        }
        array_push(self::$directoryStack, getcwd());
        chdir($path);
    }

    public static function popDir() {
        if (count(self::$directoryStack) < 1) {
            throw new OnbxIllegalStateException(
                "popDir() should be called after corresponding spawnDir()");
        }
        chdir(array_pop(self::$directoryStack));
    }
}
?>
