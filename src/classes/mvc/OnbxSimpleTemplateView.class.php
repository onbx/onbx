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
onbxImport('OnbxView');
onbxImport('OnbxMap');

class OnbxSimpleTemplateView implements OnbxView {
    const PART_SUFFIX = '.part.html';

    private $path;

    public function __construct($path) {
        if (!is_file($path) || !is_readable($path)) {
            throw new OnbxIllegalArgumentException('could not read: '.$path);
        }
        $this->path = $path;
    }

    public function render(OnbxMap $model) {
        extract($model->toNativeArray());
        require($this->path);
    }

    public function spawn($name) {
        $path = dirname($this->path).DIRECTORY_SEPARATOR.$name
            .self::PART_SUFFIX;
        return new self($path);
    }
}
?>
