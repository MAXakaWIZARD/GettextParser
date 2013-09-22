<?php

require_once('GettextParser.php');

if ($_SERVER['argv'][1]) {
    new GettextParser();
}