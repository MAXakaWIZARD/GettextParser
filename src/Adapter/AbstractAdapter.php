<?php
namespace GettextParser\Adapter;

use GettextParser\Pattern;

abstract class AbstractAdapter
{
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
    abstract protected function addPatterns();

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
