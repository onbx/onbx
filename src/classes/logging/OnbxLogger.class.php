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

onbxImport('OnbxMDC');
onbxImport('OnbxMap');

/** 
 * @since 2010-03-23
 */
class OnbxLogger {
    private $className;

    public static function getLogger($classNameOrObject) {
        static $map = array();

        $className = is_object($classNameOrObject) 
            ? get_class($classNameOrObject) 
            : $classNameOrObject;

        if (!isset($map[$className])) {
            $map[$className] = new self($className);
        }

        return $map[$className];
    }

    public function __construct($className) {
        $this->className = $className;
    }


    public function debug($message, Exception $exception = null) {
        $this->logMessage(LOG_DEBUG, $message, $exception);
    }

    public function info($message, Exception $exception = null) {
        $this->logMessage(LOG_INFO, $message, $exception);
    }

    public function warn($message, Exception $exception = null) {
        $this->logMessage(LOG_WARNING, $message, $exception);
    }

    public function error($message, Exception $exception = null) {
        $this->logMessage(LOG_ERR, $message, $exception);
    }


    private function logMessage($level, $message,
            Exception $exception = null) {

        $wholeMessage = $this->className.' - '.$message;

        if ($exception != null) {
            $wholeMessage .= "\n".get_class($exception).': '.$exception->getMessage()
                ."\n".$exception->getTraceAsString();
        }

        foreach (OnbxMDC::getContext()->toNativeArray() as $key => $object) {
            $wholeMessage .= "\n".$key."=".$this->formatObject($object);
        }

        syslog($level, $wholeMessage);
    }

    private function formatObject($object) {
        if (is_array($object)) {
            return OnbxMap::create($object)->__toString();
        } else if (is_object($object)) {
            return $object->__toString();
        }
        return $object;
    }
}
?>
