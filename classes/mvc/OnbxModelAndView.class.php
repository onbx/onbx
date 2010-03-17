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
onbxImport('OnbxView');
onbxImport('OnbxClassUtils');

class OnbxModelAndView {
    private $view;
    private $viewName;
    private $model;

    public static function create($view = null, OnbxMap $model = null) {
        return new self($view, $model);
    }

    public function __construct($view = null, OnbxMap $model = null) {
        if (is_object($view)) {
            if (!OnbxClassUtils::isInstanceOf($view, 'OnbxView')) {
                throw new OnbxIllegalArgumentException(
                    'view must be an instance of OnbxView');
            }
            $this->view = $view;
        } else {
            $this->viewName = $view;
        }
        $this->model = $model == null ? OnbxMap::create() : $model;
    }

    public function setViewName($viewName) {
        $this->viewName = $viewName;
        return $this;
    }

    public function getViewName() {
        return $this->viewName;
    }

    public function setView(OnbxView $view) {
        $this->view = $view;
        return $this;
    }

    public function getView() {
        return $this->view;
    }

    public function getModel() {
        return $this->model;
    }
}
?>
