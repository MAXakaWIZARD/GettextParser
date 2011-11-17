<?php
$gettext_pattern = array(
  "~\{\"([^\"]+)\"\|_([^\}]*)\}~",  // search for smarty modifier: {"Text to be localized"|_}
  "~\{t\}([^\{]+)\{/t\}~"           // search for smarty modifier: {t}Text to be localized{/t}
);

//define( 'RESULT_FILE', 'D:/gettext_temp.php' );
define( 'RESULT_FILE', sys_get_temp_dir() . '/poedit_' . md5( time() ) . '.php'  );
define( 'LOG_FILE', 'D:/gettext_temp_log.log' );

function _log($s, $filename)
{
	$f=fopen($filename,'wb');
	fwrite($f, $s);
	fclose($f);
}

function _append_log($s, $filename)
{
	$f=fopen($filename,'a');
	fwrite($f, $s);
	fclose($f);
}

//init log
//file_put_contents( LOG_FILE, '' );
//_append_log( print_r( $_SERVER['argv'], true ), LOG_FILE );

$params = $_SERVER['argv'];

$text = '';
$result = '';
$found_in = array();

$params_count = count( $params );
for( $k=6; $k <= $params_count; $k++ )
{
    $file = $params[$k];
    $files = explode( ';', $file );
    foreach( $files as $file )
    {
         if( $file )
         {
             if( is_file( $file ) )
             {
                $text .= file_get_contents( $file );
                $found_in[] = $file;
             }
             else
             {
                 _append_log( "File $file not found" . PHP_EOL, LOG_FILE );
             }
         }
    }
}

$mod = array();
foreach( $gettext_pattern as $pattern )
{
  preg_match_all( $pattern, $text, $regs );
  $mod = array_merge( $mod, $regs[1] );
}

if( $mod )
{
  foreach( $mod as $value )
  {
    $result .= '_("'.$value.'");'."\n";
  }
  $result = "<?php\n/*\n".implode("\n",$found_in)."*/\n\n".$result."\n?>";

  _log( $result, RESULT_FILE );
  chdir( 'C:\Program Files (x86)\Poedit\bin' );
  exec( 'xgettext.exe --force-po -o "'.$params[1].'" '.$params[2].' '.$params[3].' '.RESULT_FILE );
  //unlink( RESULT_FILE );
}
else
{
    //_append_log( 'nothing_found', LOG_FILE );
}