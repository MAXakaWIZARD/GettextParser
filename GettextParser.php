<?php

require_once('GettextParserAdapter.php');
require_once('GettextParserPattern.php');

class GettextParser
{
    /**
     * @var GettextParserAdapter
     */
    private $_adapter;

    /**
     * base path
     *
     * @var string
     */
    private $_basePath;

    /**
     * log file path
     *
     * @var string
     */
    private $_logPath;

    /**
     * result destination path
     *
     * @var string
     */
    private $_resultPath;

    /**
     * @var array
     */
    private $_phrasesList = array();

    /**
     * @var array
     */
    private $_filesList = array();

    /**
     * @var string
     */
    private $_xgettextDir = 'C:\Program Files (x86)\Poedit\bin';


    /**
     * @param $inAdapterName
     *
     * @throws Exception
     */
    public function __construct($inAdapterName)
    {
        $this->_basePath = realpath(__DIR__);

        //init files
        $this->_logPath = $this->_basePath . '/log.txt';
        $this->_resultPath = sys_get_temp_dir() . '/poedit_' . $inAdapterName . '_' . md5(microtime()) . '.php';

        if (is_string($inAdapterName)) {
            $this->loadAdapter($inAdapterName);
        } else {
            throw new Exception('AdapterName not specified');
        }
    }

    /**
     * loads adapter
     *
     * @param $inAdapterName
     *
     * @throws Exception
     *
     * @return void
     */
    private function loadAdapter($inAdapterName)
    {
        $targetClassName = 'GettextParserAdapter_' . $inAdapterName;
        $targetFilePath
            = $this->_basePath . DIRECTORY_SEPARATOR . str_replace('_', DIRECTORY_SEPARATOR, $targetClassName) . ".php";

        if (is_file($targetFilePath)) {
            require_once($targetFilePath);
            $this->_adapter = new $targetClassName;
        } else {
            throw new Exception("Cannot load Adapter {$targetClassName}");
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
     *
     * @param $inParams
     *
     * @return void
     */
    public function run($inParams)
    {
        $this->processParams($inParams);
        $this->parse();

        if (count($this->_phrasesList)) {
            if ($this->writeOutput()) {
                $this->executePoEditParser($inParams);
            }
        } else {
            $this->log('Nothing found!');
        }
    }

    /**
     * processes params and sets some variables
     *
     * @param $inParams
     *
     * @return void
     */
    private function processParams($inParams)
    {
        $this->_filesList = array();

        $paramsCount = count($inParams);
        for ($k = 7; $k < $paramsCount; $k++) {
            $this->_filesList[] = $inParams[$k];
        }
    }

    /**
     * @return void
     */
    private function parse()
    {
        $this->_phrasesList = array();

        foreach ($this->_filesList as $filePath) {
            if (is_readable($filePath)) {
                $phrases = $this->getAdapter()->parse(file_get_contents($filePath));
                if (is_array($phrases)) {
                    $this->_phrasesList = array_merge($this->_phrasesList, $phrases);
                }
            } else {
                $this->log("Cannot read file {$filePath}" . PHP_EOL);
            }
        }
    }

    /**
     * returns true on success
     *
     * @return bool
     */
    private function writeOutput()
    {
        //$this->log( print_r( $this->_phrases_list, true ) );

        foreach ($this->_phrasesList as $phrase) {
            if (is_array($phrase)) {
                //plural
                $gettextCallsBuffer .= 'ngettext(';
                foreach ($phrase as $idx => $item) {
                    if ($idx > 0) {
                        $gettextCallsBuffer .= ', ';
                    }
                    $gettextCallsBuffer .= '"' . $item . '"';
                }
                $gettextCallsBuffer .= ', 3 );' . PHP_EOL;
            } else {
                //single
                $gettextCallsBuffer .= '_("' . $phrase . '");' . PHP_EOL;
            }
        }

        $result = "<?php" . PHP_EOL . "/*" . PHP_EOL . implode(PHP_EOL, $this->_filesList) . "*/";
        $result .= str_repeat(PHP_EOL, 2) . $gettextCallsBuffer;

        return ( bool )file_put_contents($this->_resultPath, $result, FILE_BINARY);
    }

    /**
     * @param $inParams
     */
    private function executePoEditParser($inParams)
    {
        chdir($this->_xgettextDir);

        $cmd = 'xgettext.exe --force-po -o "' . $inParams[2] . '" ' . $inParams[3] . ' ' . $inParams[4] . ' '
            . $this->_resultPath;

        exec($cmd);
    }

    /**
     * writes messages to log
     *
     * @param $inMessage
     *
     * @return void
     */
    private function log($inMessage)
    {
        $f = fopen($this->_logPath, 'a');
        fwrite($f, $inMessage);
        fclose($f);
    }
}