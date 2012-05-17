<?php

define('BASE_PATH', realpath(dirname(__FILE__) . '/..'));
require_once(BASE_PATH . '/GettextParser.php');

//tests
require_once 'GettextParserJavascriptTest.php';
require_once 'GettextParserSmartyTest.php';

/**
 *
 */
class AllTestsSuite
{
    /**
     * @static
     * @return PHPUnit_Framework_TestSuite
     */
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite();

        $suite->addTestSuite(GettextParserJavascriptTest);
        $suite->addTestSuite(GettextParserSmartyTest);

        return $suite;
    }
}