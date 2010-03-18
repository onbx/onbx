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
onbxImport('OnbxRequest');
onbxImport('OnbxClassUtils');
onbxImport('OnbxController');
onbxImport('OnbxControllerResolver');

class OnbxControllerAttributeResolver implements OnbxControllerResolver {
    private $attributeName;

    public function __construct($attributeName) {
        $this->attributeName = $attributeName;
    }

    public function resolve(OnbxRequest $request) {
        if (!$request->hasAttribute($this->attributeName)) {
            return null;
        }
        $controllerName = ucfirst($request->getAttribute($this->attributeName))
            .'Controller';

        if (!OnbxClassUtils::isClassName($controllerName)) {
            throw new OnbxIllegalArgumentException('illegal controller class: '.$controllerName);
        }

        if (!OnbxClassUtils::isClassExists($controllerName)) {
            throw new OnbxIllegalArgumentException('class not found: '.$controllerName);
        }
        
        if (!OnbxClassUtils::isInstanceOf($controllerName, 'OnbxController')) {
            throw new OnbxIllegalArgumentException(
                'class '.$controllerName.' must implement OnbxController interface');
        }

        return new $controllerName;
    }
}
?>
