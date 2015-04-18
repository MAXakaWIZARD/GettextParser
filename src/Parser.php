<?php
namespace GettextParser;

use GettextParser\Adapter\AbstractAdapter;

class Parser
{
    /**
     * @var AbstractAdapter
     */
    protected $adapter;

    /**
     * base path
     *
     * @var string
     */
    protected $basePath;

    /**
     * log file path
     *
     * @var string
     */
    protected $logPath;

    /**
     * result destination path
     *
     * @var string
     */
    protected $resultPath;

    /**
     * @var array
     */
    protected $phrasesList = array();

    /**
     * @var array
     */
    protected $filesList = array();

    /**
     * @var string
     */
    protected $xgettextDir;

    /**
     * @var string
     */
    protected $xgettextBin;

    /**
     * @var string
     */
    protected $writeBuffer;

    /**
     * @param string $adapterName
     * @param string $xgettextBin
     *
     * @throws \Exception
     */
    public function __construct($adapterName, $xgettextBin)
    {
        $this->basePath = realpath(__DIR__ . '/..');

        //init files
        $this->logPath = $this->basePath . '/po_parser.log';
        $this->resultPath = sys_get_temp_dir() . '/poedit_' . $adapterName . '_' . md5(microtime()) . '.php';

        $this->setXgettextBin($xgettextBin);

        $this->loadAdapter($adapterName);
    }

    /**
     * @param $xgettextBin
     *
     * @throws \Exception
     */
    protected function setXgettextBin($xgettextBin)
    {
        if (!is_file($xgettextBin)) {
            throw new \Exception('Invalid xgettext binary path supplied');
        }

        $this->xgettextBin = $xgettextBin;
        $this->xgettextDir = dirname($this->xgettextBin);
    }

    /**
     * loads adapter
     *
     * @param $adapterName
     *
     * @throws \Exception
     *
     * @return void
     */
    protected function loadAdapter($adapterName)
    {
        if (!is_string($adapterName) || $adapterName === '') {
            throw new \Exception('Invalid adapterName supplied');
        }

        $targetClassName = '\\GettextParser\\Adapter\\' . $adapterName;

        if (class_exists($targetClassName)) {
            $this->adapter = new $targetClassName;
        } else {
            throw new \Exception("Cannot load Adapter \"{$adapterName}\"");
        }
    }

    /**
     * @return AbstractAdapter
     */
    public function getAdapter()
    {
        return $this->adapter;
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
        $this->log(implode(' ', $params));

        $this->processParams($params);
        $this->parse();

        if (!count($this->phrasesList)) {
            $this->log('Nothing found!');
        }

        if ($this->writeOutput()) {
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
    protected function processParams($params)
    {
        $this->filesList = array();

        $paramsCount = count($params);
        for ($k = 7; $k < $paramsCount; $k++) {
            $this->filesList[] = $params[$k];
        }
    }

    /**
     * @return void
     */
    protected function parse()
    {
        $this->phrasesList = array();

        foreach ($this->filesList as $filePath) {
            if (is_readable($filePath)) {
                $phrases = $this->getAdapter()->parse(file_get_contents($filePath));
                if (is_array($phrases)) {
                    $this->phrasesList = array_merge($this->phrasesList, $phrases);
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
    protected function writeOutput()
    {
        $this->writeHeader();

        foreach ($this->phrasesList as $phrase) {
            $this->writePhrase($phrase);
        }

        return (bool) file_put_contents($this->resultPath, $this->writeBuffer, FILE_BINARY);
    }

    /**
     *
     */
    protected function writeHeader()
    {
        $this->writeBuffer = "<?php" . PHP_EOL . "/*" . PHP_EOL;
        $this->writeBuffer .= implode(PHP_EOL, $this->filesList);
        $this->writeBuffer .= PHP_EOL . "*/";
        $this->writeBuffer .= str_repeat(PHP_EOL, 2);
    }

    /**
     * @param $phrase
     */
    protected function writePhrase($phrase)
    {
        if (is_array($phrase)) {
            //plural
            $this->writeBuffer .= 'ngettext(';
            foreach ($phrase as $idx => $item) {
                if ($idx > 0) {
                    $this->writeBuffer .= ', ';
                }
                $this->writeBuffer .= '"' . $item . '"';
            }
            $this->writeBuffer .= ', 3);' . PHP_EOL;
        } else {
            //single
            $this->writeBuffer .= '_("' . $phrase . '");' . PHP_EOL;
        }
    }

    /**
     * @param $params
     */
    protected function executePoEditParser($params)
    {
        chdir($this->xgettextDir);

        $cmd = $this->xgettextBin . ' --force-po -o "' . $params[2] . '" ' . $params[3] . ' ' . $params[4] . ' "'
            . $this->resultPath . '"';

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
    protected function log($message)
    {
        $f = fopen($this->logPath, 'a');
        fwrite($f, $message . PHP_EOL);
        fclose($f);
    }

    /**
     * @return string
     */
    public function getResultPath()
    {
        return $this->resultPath;
    }
}
