<?php

require_once( 'GettextParser.php' );
require_once( 'GettextParserAdapter.php' );

$parser = new GettextParser( 'Smarty' );
$parser->parse( $_SERVER['argv'] );