<?php

class GettextParserPattern
{
    /**
     * regular expression
     * @var string
     */
    private $pattern = '';
    /**
     * @var bool
     */
    private $isPlural = false;

    public function __construct($pattern, $isPlural = false)
    {
        $this->pattern = $pattern;
        $this->isPlural = $isPlural;
    }

    public function match($data)
    {
        $result = preg_match_all($this->getPattern(), $data, $matches, PREG_SET_ORDER);
        if ($result !== false && $result > 0) {
            $results = array();
            foreach ($matches as $match) {
                if ($this->isPlural()) {
                    $start_pos = 1;
                    $length = count($match) - $start_pos - 1;
                    $results[] = array_slice($match, $start_pos, $length);
                } else {
                    $results[] = $match[1];
                }
            }

            return $results;
        } else {
            return false;
        }
    }

    /**
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * @return boolean
     */
    public function isPlural()
    {
        return $this->isPlural;
    }
}