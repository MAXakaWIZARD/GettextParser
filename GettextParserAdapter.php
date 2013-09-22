<?php

abstract class GettextParserAdapter {

    /**
     * stores parsing patterns
     * @var array of GettextParserPattern
     */
    protected $_patterns = array();

    /**
     * stores custom functions
     * @var array of PoEdit function names
     */
    protected $_functionsList = array();

    public function __construct($functionsList = "")
    {
        $this->_functionsList = (!empty($functionsList)) ? $functionsList : array("_");
    }

    /**
     * @param $inData
     * @return array
     */
    public function parse($inData)
    {
        $results = array();

        foreach ($this->_patterns as $pattern)
        {
            $result = $pattern->match($inData);
            if ($result !== false)
            {
                $results = array_merge($results, $result);
            }
        }

        return $results;
    }

}