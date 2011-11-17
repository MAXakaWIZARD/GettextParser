<?php

require_once( 'GettextParserAdapter.php' );

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
        $this->_log_path = $this->_base_path . '/log_' . date( 'Y-m-d_His' ) . '.txt';
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
        $targetFilePath = $this->_base_path . DIRECTORY_SEPARATOR . str_replace( '_', DIRECTORY_SEPARATOR, $targetClassName ) . ".php";

        if( is_file( $targetFilePath ) )
        {
            require_once( $targetFilePath );
            $this->_adapter = new $targetClassName;
        }
        else
        {
            throw new Exception( "Cannot load Adapter {$targetClassName}" );
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
        $this->log( print_r( $inParams, true ) );
        
        $text = '';
        $result = '';
        $files_list = array();

        $params_count = count( $inParams );
        for( $k = 7; $k <= $params_count; $k++ )
        {
             $file_path = $inParams[$k];
             if( is_file( $file_path ) )
             {
                $text .= file_get_contents( $file_path );
                $files_list[] = $file_path;
             }
             else
             {
                 $this->log( "File {$file_path} not found" . PHP_EOL );
             }
        }

        $mod = $this->getAdapter()->parse( $text );

        if( $mod )
        {
            foreach( $mod as $value )
            {
                $result .= '_("'.$value.'");'."\n";
            }
            $result = "<?php\n/*\n".implode("\n",$files_list)."*/\n\n".$result."\n";

            $this->writeData( $result );
            chdir( $this->_xgettext_dir );
            exec( 'xgettext.exe --force-po -o "'.$inParams[2].'" '.$inParams[3].' '.$inParams[4].' '.$this->_result_path );
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