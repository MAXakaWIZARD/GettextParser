<?php

require_once('GettextParserAdapter.php');
require_once('GettextParserPattern.php');

class GettextParser {

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
     * @var array
     */
    private $_functionsList = array();

    /**
     * @var string
     */
    private $_xgettextDir = 'C:\Program Files (x86)\Poedit\bin';

    /**
     * @param $adapterName
     *
     * @throws Exception
     */
    public function __construct($params = "")
    {
        $this->_basePath = realpath(__DIR__);

        if (empty($params))
        {
            $params = $_SERVER['argv'];
        }

        $adapterName = $_SERVER['argv'][1];

        //init files
        $this->_logPath = $this->_basePath . '/log.txt';
        $this->_resultPath = sys_get_temp_dir() . '/poedit_' . $adapterName . '_' . md5(microtime()) . '.php';

        if (is_string($adapterName))
        {
            $this->log(implode(' ', $params));
            $this->processParams($params);
            $this->loadAdapter($adapterName);
            $this->run($params);
        } else
        {
            throw new Exception('AdapterName not specified');
        }
    }

    /**
     * loads adapter
     *
     * @param $adapterName
     *
     * @throws Exception
     *
     * @return void
     */
    private function loadAdapter($adapterName)
    {
        $targetClassName = 'GettextParserAdapter_' . $adapterName;
        $targetFilePath
                = $this->_basePath . DIRECTORY_SEPARATOR . str_replace('_', DIRECTORY_SEPARATOR, $targetClassName) . ".php";

        if (is_file($targetFilePath))
        {
            require_once($targetFilePath);
            $this->_adapter = new $targetClassName($this->_functionsList);
        } else
        {
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
     * @param $params
     *
     * @return void
     */
    public function run($params)
    {
        $this->parse();

        if (!count($this->_phrasesList))
        {
            $this->log('Nothing found!');
        }

        if ($this->writeOutput())
        {
            $this->executePoEditParser($params);
        }
    }

    /**
     * processes params and sets some variables
     *
     * @param $params
     *
     * @return void
     */
    private function processParams($params)
    {
        $this->_functionsList = array();
        $this->_filesList = array();

        foreach ($params AS $v)
        {
            if (preg_match('#-k([^\s]+)#i', $v, $matches))
            {
                $this->_functionsList[] = $matches[1];
            } else if (preg_match('#(\.tpl|\.html|\.htm)$#i', $v))
            {
                $this->_filesList[] = preg_replace('#^-#', '', $v);
            }
        }
    }

    /**
     * @return void
     */
    private function parse()
    {
        $this->_phrasesList = array();

        foreach ($this->_filesList as $filePath)
        {
            if (is_readable($filePath))
            {
                $phrases = $this->getAdapter()->parse(file_get_contents($filePath));
                if (is_array($phrases))
                {
                    $this->_phrasesList = array_merge($this->_phrasesList, $phrases);
                }
            } else
            {
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
        $gettextCallsBuffer = '';

        foreach ($this->_phrasesList as $phrase)
        {
            if (is_array($phrase))
            {
                //plural
                $gettextCallsBuffer .= 'ngettext(';
                foreach ($phrase as $idx => $item)
                {
                    if ($idx > 0)
                    {
                        $gettextCallsBuffer .= ', ';
                    }
                    $gettextCallsBuffer .= '"' . $item . '"';
                }
                $gettextCallsBuffer .= ', 3);' . PHP_EOL;
            } else
            {
                //single
                $gettextCallsBuffer .= '_("' . $phrase . '");' . PHP_EOL;
            }
        }

        $result = "<?php" . PHP_EOL . "/*" . PHP_EOL . implode(PHP_EOL, $this->_filesList) . "*/";
        $result .= str_repeat(PHP_EOL, 2) . $gettextCallsBuffer;

        return (bool) file_put_contents($this->_resultPath, $result, FILE_BINARY);
    }

    /**
     * @param $params
     */
    private function executePoEditParser($params)
    {
        chdir($this->_xgettextDir);

        $cmd = 'xgettext.exe --force-po -o "' . $params[2] . '" ' . $params[3] . ' ' . $params[4] . ' "'
                . $this->_resultPath . '"';

        $this->log($cmd);

        exec($cmd);
    }

    /**
     * writes messages to log
     *
     * @param $message
     *
     * @return void
     */
    private function log($message)
    {
        $f = fopen($this->_logPath, 'a');
        fwrite($f, $message . PHP_EOL);
        fclose($f);
    }

}