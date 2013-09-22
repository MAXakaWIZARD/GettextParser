<?php
/**
 *
 */
class GettextParserSmartyTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var GettextParser
     */
    private $_parser;

    /**
     * @var GettextParser_Adapter
     */
    private $_adapter;

    /**
     *
     */
    public function setUp()
    {
        $this->_parser = new GettextParser('Smarty');
        $this->_adapter = $this->_parser->getAdapter();
    }

    /**
     *
     */
    public function tearDown()
    {

    }

    /**
     * @dataProvider providerSingle
     *
     * @param $inString
     * @param $inResult
     */
    public function testSingle($inString, $inResult)
    {
        $this->assertEquals($inResult, $this->_adapter->parse($inString));
    }

    /**
     * @return array
     */
    public function providerSingle()
    {
        return array(
            array(
                "{t}cat{/t}",
                array('cat')
            ),
            array(
                '{_("Text to be localized")}',
                array('Text to be localized')
            ),
            array(
                '{"Text to be localized"|_}',
                array('Text to be localized')
            )
        );
    }

    /**
     * @dataProvider providerPlural
     *
     * @param $inString
     * @param $inResult
     */
    public function testPlural($inString, $inResult)
    {
        $this->assertEquals($inResult, $this->_adapter->parse($inString));
    }

    /**
     * @return array
     */
    public function providerPlural()
    {
        return array(
            array(
                "{t}cat{/t}",
                array('cat')
            )
        );
    }
}