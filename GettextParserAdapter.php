<?php

abstract class GettextParserAdapter
{
    /**
     * array, that stores parsing patterns
     * @var array
     */
    protected $_patterns = array();

    /**
     * @param $inData
     * @return array
     */
    public function parse( $inData )
    {
        $results = array();
        foreach( $this->_patterns as $pattern )
        {
            preg_match_all( $pattern, $inData, $matches );
            $results = array_merge( $results, $matches[1] );
        }

        return $results;
    }
}