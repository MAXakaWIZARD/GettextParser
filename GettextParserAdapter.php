<?php

abstract class GettextParserAdapter
{
    /**
     * stores parsing patterns
     * @var array of GettextParserPattern
     */
    protected $patterns = array();

    /**
     * @param $data
     * @return array
     */
    public function parse($data)
    {
        $results = array();

        foreach ($this->patterns as $pattern) {
            $result = $pattern->match($data);
            if ($result !== false) {
                $results = array_merge($results, $result);
            }
        }

        return $results;
    }
}