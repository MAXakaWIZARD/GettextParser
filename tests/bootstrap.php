<?php
error_reporting(E_ALL);

define('BASE_PATH', realpath(dirname(__FILE__) . '/..'));
define('TEST_DATA_PATH', BASE_PATH . "/tests/data");

$configs = array(
    'config.php',
    'config.dist.php'
);
foreach ($configs as $configName) {
    if (file_exists(BASE_PATH . '/' . $configName)) {
        $config = include(BASE_PATH . '/' . $configName);
        break;
    }
}

if (!isset($config['xgettext_bin'])) {
    throw new \Exception('Please provide valid config');
}

define('XGETTEXT_BIN', $config['xgettext_bin']);

require_once(BASE_PATH . '/vendor/autoload.php');
