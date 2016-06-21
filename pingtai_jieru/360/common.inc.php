<?php

//是否打开调试模式，打开后，可以查看oauth日志
//上线时，将调试设置成false. 线上记录日志，会导致产生大量日志，占用磁盘空间。
//设置为true将打开日志
require_once  '../unity/self_error_code.php';
define('QIHOO_MSDK_DEBUG', true);

if (stripos(PHP_OS, 'win') === 0) {
    //windows 日志路径为当前目录 qihoo_msdk.log
    define('QIHOO_MSDK_LOG', dirname(__FILE__) . '/qihoo_msdk.log');
} else {
    //*nix 日志路径为/tmp/qihoo_msdk.log
    define('QIHOO_MSDK_LOG', '/tmp/qihoo_msdk.log');
}

// 暗黑召唤神 key
//TODO::在此处添加应用 app_key=>app_secret 配置
$_keyStore = array(
    '3595aa8c8692ce555d3c1f12ca157792' => '147b2b12ea49aebcdb563f6ec40ff3f8',
);

if (!isset($_REQUEST['app_key'])) {
	exit("Url param error:".ErrorCode::URL_HAS_NO_APP_KEY);	
}

foreach ($_keyStore as $x_key=>$x_value) {
	if ($_REQUEST['app_key'] != $x_key) {
		exit("Url param error:".ErrorCode::ERROR_APP_KEY);	
	}
}

$appKey = $_REQUEST['app_key'];
$appSecret = $_keyStore[$appKey];

define('QIHOO_APP_KEY', $appKey);
define('QIHOO_APP_SECRET', $appSecret);

define('QIHOO_MSDK_ROOT', realpath(dirname(__FILE__) . '/qihoo_msdk/'));

function qihooLoad($className)
{
    static $loadedClassList = array();
    if (!empty($loadedClassList[$className])) {
        return;
    }
    $path = str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
    require_once QIHOO_MSDK_ROOT . DIRECTORY_SEPARATOR . $path;
    $loadedClassList[$className] = 1;
}

spl_autoload_register('qihooLoad');

