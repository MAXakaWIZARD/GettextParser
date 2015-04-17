<?php
namespace GettextParser\Tests;

use GettextParser\Parser;
use GettextParser\Adapter;

/**
 *
 */
class SmartyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var Adapter\Smarty
     */
    private $adapter;

    /**
     *
     */
    public function setUp()
    {
        $this->parser = new Parser('Smarty', XGETTEXT_BIN);
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
        $this->assertEquals('GettextParser\\Adapter\\Smarty', get_class($this->adapter));
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
            ),
            array(
                '{t}User %FROM_EMAIL% has shared a video "%PLAYLIST_TITLE%" with you.{/t}',
                array('User %FROM_EMAIL% has shared a video "%PLAYLIST_TITLE%" with you.')
            ),
            array(
                '{t}You can watch it <a href="%PLAYLIST_URL%">here</a>{/t}',
                array('You can watch it <a href="%PLAYLIST_URL%">here</a>')
            ),
            array(
                '{t sprintf_args=[$helper->getConfigOption(\'invite_reg_referal_bonus\')]}'
                . 'Invite friend and get +%d EUR to your account!' . '{/t}',
                array('Invite friend and get +%d EUR to your account!')
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
