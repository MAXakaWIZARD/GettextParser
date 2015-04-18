<?php
error_reporting(E_ALL);

define('BASE_PATH', realpath(dirname(__FILE__)));
require_once(BASE_PATH . '/vendor/autoload.php');

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

if ($_SERVER['argv'][1]) {
    $parser = new \GettextParser\Parser($_SERVER['argv'][1], $config['xgettext_bin']);
    $parser->run($_SERVER['argv']);
}
