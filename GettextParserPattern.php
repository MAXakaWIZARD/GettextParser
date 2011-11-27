<?php

class GettextParserPattern
{
    /**
     * regular expression
     * @var string
     */
    private $_pattern = '';

    /**
     * @var bool
     */
    private $_is_plural = false;
    

    public function __construct( $inPattern, $inIsPlural = false )
    {
        $this->_pattern = $inPattern;
        $this->_is_plural = $inIsPlural;
    }

    /**
     * @return boolean
     */
    public function isPlural()
    {
        return $this->_is_plural;
    }

    /**
     * @return string
     */
    public function getPattern()
    {
        return $this->_pattern;
    }

    public function match( $inData )
    {
        $result = preg_match_all( $this->getPattern(), $inData, $matches, PREG_SET_ORDER );
        if( $result !== false && $result > 0 )
        {
            $results = array();
            foreach( $matches as $match )
            {
                if( $this->isPlural() )
                {
                    $start_pos = 1;
                    $length = count( $match ) - $start_pos - 1;
                    $results[] = array_slice( $match, $start_pos, $length );
                }
                else
                {
                    $results[] = $match[ 1 ];
                }
            }

            return $results;
        }
        else
        {
            return false;
        }
    }
}