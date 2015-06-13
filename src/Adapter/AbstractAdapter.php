<?php
namespace GettextParser\Adapter;

use GettextParser\Pattern;

abstract class AbstractAdapter
{
    /**
     * @var array
     */
    protected $patternConfig = array();

    /**
     * stores parsing patterns
     * @var Pattern[]
     */
    protected $patterns = array();

    /**
     *
     */
    public function __construct()
    {
        $this->addPatterns();
    }

    /**
     *
     */
    protected function addPatterns()
    {
        foreach ($this->patternConfig as $item) {
            foreach ($item['pattern'] as $pattern) {
                $this->patterns[] = new Pattern($pattern, $item['plural']);
            }
        }
    }

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
