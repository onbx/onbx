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
onbxImport('OnbxControllerResolver');
onbxImport('OnbxViewResolver');

class OnbxDispatcher {
    private $viewResolver;
    private $controllerResolver;

    public static function create() {
        return new OnbxDispatcher();
    }

    public function dispatchRequest(OnbxRequest $request) {
        $this->initialCheck();

        $controller = $this->controllerResolver->resolve($request);
        if ($controller == null) {
            throw new OnbxIllegalArgumentException('controller could not be resolved');
        }

        $mav = $controller->handleRequest($request);

        if ($mav->getView() != null) {
            $view = $mav->getView();
        } else {
            $view = $this->viewResolver->resolveViewName($mav->getViewName());
            if ($view == null) {
                throw new OnbxIllegalStateException('view could not be resolved: '.$mav->getViewName());
            }
        }

        $view->render($mav->getModel());
    }

    public function setControllerResolver(OnbxControllerResolver $controllerResolver) {
        $this->controllerResolver = $controllerResolver;
        return $this;
    }

    public function getControllerResolver() {
        return $this->controllerResolver;
    }

    public function setViewResolver(OnbxViewResolver $viewResolver) {
        $this->viewResolver = $viewResolver;
        return $this;
    }

    public function getViewResolver() {
        return $this->viewResolver;
    }

    private function initialCheck() {
        if ($this->viewResolver == null) {
            throw new OnbxIllegalStateException('viewResolver is not injected');
        }
        if ($this->controllerResolver == null) {
            throw new OnbxIllegalStateException('controllerResolver is not injected');
        }
    }
}
?>
