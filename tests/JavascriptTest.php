<?php
namespace GettextParser\Tests;

/**
 *
 */
class JavascriptTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var GettextParser
     */
    private $parser;

    /**
     * @var GettextParser_Adapter
     */
    private $adapter;

    /**
     *
     */
    public function setUp()
    {
        $this->parser = new \GettextParser\Parser('JavaScript');
        $this->adapter = $this->parser->getAdapter();
    }

    /**
     *
     */
    public function tearDown()
    {

    }

    /**
     *
     */
    public function testAdapter()
    {
        $this->assertEquals('GettextParser\\Adapter\\JavaScript', get_class($this->adapter));
    }

    /**
     * @dataProvider providerSingle
     *
     * @param $string
     * @param $result
     */
    public function testSingle($string, $result)
    {
        $this->assertEquals($result, $this->adapter->parse($string));
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
     * @param $string
     * @param $result
     */
    public function testPlural($string, $result)
    {
        $this->assertEquals($result, $this->adapter->parse($string));
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