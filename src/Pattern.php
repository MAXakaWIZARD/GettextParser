<?php
namespace GettextParser;

class Pattern
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

    /**
     * @param $pattern
     * @param bool $isPlural
     */
    public function __construct($pattern, $isPlural = false)
    {
        $this->pattern = $pattern;
        $this->isPlural = $isPlural;
    }

    /**
     * @param $data
     * @return array|bool
     */
    public function match($data)
    {
        $result = preg_match_all($this->getPattern(), $data, $matches, PREG_SET_ORDER);
        if ($result === false || $result === 0) {
            return false;
        }

        $results = array();
        foreach ($matches as $match) {
            if ($this->isPlural()) {
                $startPos = 1;
                $length = count($match) - $startPos - 1;
                $results[] = array_slice($match, $startPos, $length);
            } else {
                $results[] = $match[1];
            }
        }

        return $results;
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
