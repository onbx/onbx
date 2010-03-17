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
 * Based on onPHP's FileUtils class by Sveta A. Smirnova.
 * 
 * @since 2010-03-17
 */
onbxImport('OnbxStaticFactory');

final class OnbxFileUtils extends OnbxStaticFactory {
    public static function removeDirectory($directory, $recursive = false) {
        if (!$recursive) {
            rmdir($directory);
        } else {
            $directoryIterator = new DirectoryIterator($directory);

            foreach ($directoryIterator as $file) {
                if ($file->isDot())
                    continue;

                if ($file->isDir())
                    self::removeDirectory($file->getPathname(), $recursive);
                elseif (!@unlink($file->getPathname()))
                    throw new OnbxIllegalStateException(
                        "cannot unlink {$file->getPathname()}"
                    );
            }

            rmdir($directory);
        }
    }
}
?>
