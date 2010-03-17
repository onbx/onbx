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
 * Based on onPHP's ClassUtils.
 * 
 * @since 2010-03-11
 */
onbxImport('OnbxStaticFactory');

final class OnbxClassUtils extends OnbxStaticFactory {
    const CLASS_NAME_PATTERN = '[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*';

    public static function isClassName($className) {
        if (!is_string($className))
            return false;
        return preg_match('/^'.self::CLASS_NAME_PATTERN.'$/', $className) > 0;
    }

    public static function isClassImplements($what) {
        static $classImplements = null;

        if (!$classImplements) {
            if (!function_exists('class_implements')) {
                $classImplements = create_function(
                    '$what',
                    '
                    $info = new ReflectionClass($what);
                    return $info->getInterfaceNames();
                    '
                );
            } else {
                $classImplements = 'class_implements';
            }
        }

        return $classImplements($what, true);
    }

    public static function isInstanceOf($object, $class) {
        if (is_object($class)) {
            $className = get_class($class);
        } elseif (is_string($class)) {
            $className = $class;
        } else {
            throw new OnbxIllegalArgumentException('strange class given');
        }

        if (
            is_string($object)
            && self::isClassName($object)
        ) {
            if ($object == $className)
                return true;
            elseif (is_subclass_of($object, $className))
                return true;
            else
                return in_array(
                    $class,
                    self::isClassImplements($object)
                );
        } elseif (is_object($object)) {
            return $object instanceof $className;

        } else {
            throw new OnbxIllegalArgumentException('strange object given');
        }
    }

    public static function isClassExists($className) {
        if (!self::isClassName($className)) {
            throw new OnbxIllegalArgumentException('strange class given: ' + $className);
        }
        $paths = explode(PATH_SEPARATOR, get_include_path());
        foreach ($paths as $path) {
            if (is_readable($path.DIRECTORY_SEPARATOR.$className.'.class.php')) {
                onbxImport($className);
                return class_exists($className, false);
            }
        }
        return false;
    }
}
?>
