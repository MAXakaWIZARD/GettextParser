<?php
/**
 *
 */
class GettextParserJavascriptTest extends PHPUnit_Framework_TestCase
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
        $this->_parser = new GettextParser('JavaScript');
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
                "_('cat');",
                array('cat')
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
                "n_('cat','cats',3);",
                array(array('cat', 'cats'))
            ),
            array(
                "n_('cat','cats', smartSearch.params.countries.length )",
                array(array('cat', 'cats'))
            ),
            array(
                "n_('match','matches',matches_count);$('#es_chosen_matches .visible_title').hide();",
                array(array('match', 'matches'))
            ),
            array(
                "n_( 'день', 'дней', 3 );",
                array(array('день', 'дней'))
            )
        );
    }
}