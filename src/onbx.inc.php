<?php
/**
 * @copyright (c) 2010, Ivan Y. Khvostishkov <ivan.khvostishkov@webvisor.ru>
 * @since 2010-03-09
 */
define('ONBX_VERSION', '0.3');

// uncomment this error reporting to get some lulz, or see OnbxSanitizer:
//error_reporting(E_ALL | E_STRICT);

define('ONBX_ROOT_PATH', dirname(__FILE__).DIRECTORY_SEPARATOR);
define('ONBX_CORE_PATH', ONBX_ROOT_PATH.'classes'.DIRECTORY_SEPARATOR);

set_include_path(
    get_include_path().PATH_SEPARATOR
    .ONBX_CORE_PATH.DIRECTORY_SEPARATOR.'exceptions'.PATH_SEPARATOR
    .ONBX_CORE_PATH.DIRECTORY_SEPARATOR.'core'.PATH_SEPARATOR
    .ONBX_CORE_PATH.DIRECTORY_SEPARATOR.'cache'.PATH_SEPARATOR
    .ONBX_CORE_PATH.DIRECTORY_SEPARATOR.'logging'.PATH_SEPARATOR
    .ONBX_CORE_PATH.DIRECTORY_SEPARATOR.'http'.PATH_SEPARATOR
    .ONBX_CORE_PATH.DIRECTORY_SEPARATOR.'mvc'.PATH_SEPARATOR
);


function onbxImport($className) {
    $reportingLevel = error_reporting();
    error_reporting(E_ALL | E_STRICT);
    require_once($className.'.class.php');
    error_reporting($reportingLevel);
}

onbxImport('OnbxInit');
OnbxInit::main();
?>
