<?php
/**
 *
 */
class GettextParserSmartyTest extends PHPUnit_Framework_TestCase
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
        $this->parser = new GettextParser('Smarty');
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
        $this->assertEquals('GettextParserAdapter_Smarty', get_class($this->adapter));
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
                "{t}cat{/t}",
                array('cat')
            )
        );
    }
}