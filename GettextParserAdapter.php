<?php

abstract class GettextParserAdapter
{
    /**
     * stores parsing patterns
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
            $result = preg_match_all( $pattern, $inData, $matches, PREG_PATTERN_ORDER );
            if( $result !== false && $result > 0 )
            {
                $results = array_merge( $results, $matches[1] );
            }
        }

        return $results;
    }
}