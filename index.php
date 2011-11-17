<?php

require_once( 'GettextParser.php' );

if( $_SERVER['argv'][1] )
{
    $parser = new GettextParser( $_SERVER['argv'][1] );
    $parser->run( $_SERVER['argv'] );
}