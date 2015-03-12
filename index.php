<?php
error_reporting(E_ALL);

require_once('./vendor/autoload.php');

if ($_SERVER['argv'][1]) {
    $parser = new \GettextParser\Parser($_SERVER['argv'][1]);
    $parser->run($_SERVER['argv']);
}