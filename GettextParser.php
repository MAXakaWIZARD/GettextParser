<?php

class GettextParser
{
    /**
     * @var GettextParserAdapter
     */
    private $_adapter;

    /**
     * base path
     * @var string
     */
    private $_base_path;

    /**
     * log file path
     * @var string
     */
    private $_log_path;

    /**
     * result destination path
     * @var string
     */
    private $_result_path;

    /**
     * @var string
     */
    private $_xgettext_dir = 'C:\Program Files (x86)\Poedit\bin';


    /**
     * @param $inAdapterName
     */
    public function __construct( $inAdapterName )
    {
        $this->_base_path = realpath( __DIR__ );

        //init files
        $this->_log_path = $this->_base_path . '/log_' . date( 'Y-m-d H:i:s' ) . '.txt';
        $this->_result_path = sys_get_temp_dir() . '/poedit_' . md5( time() ) . '.php';

        if( is_string( $inAdapterName ) )
        {
            $this->loadAdapter( $inAdapterName );
        }
    }

    /**
     * @param $inAdapterName
     * @return void
     */
    public function loadAdapter( $inAdapterName )
    {
        $targetClassName = 'GettextParserAdapter_' . $inAdapterName;
        $targetFilePath = "GettextParserAdapter/{$inAdapterName}.php";

        if( is_file( $targetFilePath ) )
        {
            require_once( $targetFilePath );
            $this->_adapter = new $targetClassName;
        }
    }

    /**
     * @return GettextParserAdapter
     */
    public function getAdapter()
    {
        return $this->_adapter;
    }

    /**
     * @param $inParams
     * @return void
     */
    public function parse( $inParams )
    {
        $text = '';
        $result = '';
        $found_in = array();

        $params_count = count( $inParams );
        for( $k=6; $k <= $params_count; $k++ )
        {
            $file = $inParams[$k];
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
                         $this->log( "File $file not found" . PHP_EOL );
                     }
                 }
            }
        }

        $mod = $this->getAdapter()->parse( $text );

        if( $mod )
        {
          foreach( $mod as $value )
          {
              $result .= '_("'.$value.'");'."\n";
          }
          $result = "<?php\n/*\n".implode("\n",$found_in)."*/\n\n".$result."\n?>";

          $this->writeData( $result );
          chdir( $this->_xgettext_dir );
          exec( 'xgettext.exe --force-po -o "'.$inParams[1].'" '.$inParams[2].' '.$inParams[3].' '.$this->_result_path );
        }
        else
        {
            $this->log( 'Nothing found!' );
        }
    }

    /**
     * @param $inData
     * @return void
     */
    private function writeData( $inData )
    {
        $f = fopen( $this->_result_path, 'wb' );
        fwrite( $f, $inData );
        fclose( $f );
    }

    /**
     * @param $inMessage
     * @return void
     */
    private function log( $inMessage )
    {
        $f = fopen( $this->_log_path, 'a' );
        fwrite( $f, $inMessage );
        fclose( $f );
    }
}