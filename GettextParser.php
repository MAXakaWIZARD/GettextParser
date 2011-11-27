<?php

require_once( 'GettextParserAdapter.php' );
require_once( 'GettextParserPattern.php' );

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
     * @var array
     */
    private $_phrases_list = array();

    /**
     * @var array
     */
    private $_files_list = array();

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
        $this->_log_path = $this->_base_path . '/log.txt';
        $this->_result_path = sys_get_temp_dir() . '/poedit_' . $inAdapterName . '_' . md5( microtime() ) . '.php';

        if( is_string( $inAdapterName ) )
        {
            $this->loadAdapter( $inAdapterName );
        }
        else
        {
            throw new Exception( 'AdapterName not specified' );
        }
    }

    /**
     * loads adapter
     * @param $inAdapterName
     * @return void
     */
    private function loadAdapter( $inAdapterName )
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
     * performs files parsing and generates output for poEdit parser
     * @param $inParams
     * @return void
     */
    public function run( $inParams )
    {
        $this->processParams( $inParams );
        $this->parse();

        if( count( $this->_phrases_list ) )
        {
            if( $this->writeOutput() )
            {
                $this->executePoEditParser( $inParams );
            }
        }
        else
        {
            $this->log( 'Nothing found!' );
        }
    }

    /**
     * processes params and sets some variables
     * @param $inParams
     * @return void
     */
    private function processParams( $inParams )
    {
        $this->_files_list = array();

        $params_count = count( $inParams );
        for( $k = 7; $k < $params_count; $k++ )
        {
             $this->_files_list[] = $inParams[$k];
        }
    }

    /**
     * @return void
     */
    private function parse()
    {
        $this->_phrases_list = array();

        foreach( $this->_files_list as $file_path )
        {
            if( is_readable( $file_path ) )
            {
                $phrases = $this->getAdapter()->parse( file_get_contents( $file_path ) );
                if( is_array( $phrases ) )
                {
                    $this->_phrases_list = array_merge( $this->_phrases_list, $phrases );
                }
            }
            else
            {
                $this->log( "Cannot read file {$file_path}" . PHP_EOL );
            }
        }
    }

    /**
     * returns true on success
     * @return bool
     */
    private function writeOutput()
    {
        //$this->log( print_r( $this->_phrases_list, true ) );

        foreach( $this->_phrases_list as $phrase )
        {
            if( is_array( $phrase ) )
            {
                //plural
                $gettext_calls_buffer .= 'ngettext(';
                foreach( $phrase as $idx => $item )
                {
                    if( $idx > 0 )
                    {
                        $gettext_calls_buffer .= ', ';
                    }
                    $gettext_calls_buffer .= '"'.$item.'"';
                }
                $gettext_calls_buffer .= ', 3 );' . PHP_EOL;
            }
            else
            {
                //single
                $gettext_calls_buffer .= '_("'.$phrase.'");' . PHP_EOL;
            }
        }

        $result = "<?php" . PHP_EOL . "/*" . PHP_EOL . implode( PHP_EOL, $this->_files_list ) . "*/";
        $result .= str_repeat( PHP_EOL, 2 ) . $gettext_calls_buffer;

        return ( bool ) file_put_contents( $this->_result_path, $result, FILE_BINARY );
    }

    /**
     * @return void
     */
    private function executePoEditParser( $inParams )
    {
        chdir( $this->_xgettext_dir );

        $cmd = 'xgettext.exe --force-po -o "'.$inParams[2].'" '.$inParams[3].' '.$inParams[4].' '.$this->_result_path;

        exec( $cmd );
    }

    /**
     * writes messages to log
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