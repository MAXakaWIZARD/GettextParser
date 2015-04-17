<?php
namespace GettextParser\Tests;

use GettextParser\Parser;
use GettextParser\Adapter;

/**
 *
 */
class ParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     */
    public function tearDown()
    {
        @unlink(TEST_DATA_PATH . '/tmp.pot');
    }

    /**
     *
     */
    public function testWrongEmptyAdapterName()
    {
        $this->setExpectedException('\Exception', 'Invalid adapterName supplied');
        $parser = new Parser('', XGETTEXT_BIN);
    }

    /**
     *
     */
    public function testWrongXgettextPath()
    {
        $this->setExpectedException('\Exception', 'Invalid xgettext binary path supplied');
        $parser = new Parser('Smarty', '/path/to/whatever/dir/xgettext');
    }

    /**
     *
     */
    public function testWrongAdapter()
    {
        $this->setExpectedException('\Exception', "Cannot load Adapter \"UnknownAdapter\"");
        $parser = new Parser('UnknownAdapter', XGETTEXT_BIN);
    }

    /**
     *
     */
    public function testGeneral()
    {
        $parser = new Parser('Smarty', XGETTEXT_BIN);
        $parser->run($this->getParserParams('Smarty', TEST_DATA_PATH . '/general.tpl'));
    }

    /**
     *
     */
    public function testEmpty()
    {
        $parser = new Parser('Smarty', XGETTEXT_BIN);
        $parser->run($this->getParserParams('Smarty', TEST_DATA_PATH . '/empty.tpl'));
    }

    /**
     *
     */
    public function testPlurals()
    {
        $parser = new Parser('JavaScript', XGETTEXT_BIN);
        $parser->run($this->getParserParams('JavaScript', TEST_DATA_PATH . '/plurals.js'));
    }

    /**
     *
     */
    public function testUnreadable()
    {
        $tmpFilePath = TEST_DATA_PATH . '/general_tmp.tpl';
        file_put_contents($tmpFilePath, "{t}cat{/t}");
        $this->assertFileExists($tmpFilePath);
        @chmod($tmpFilePath, 0000);

        $parser = new Parser('Smarty', XGETTEXT_BIN);
        $parser->run($this->getParserParams('Smarty', $tmpFilePath));

        $this->assertFileExists($tmpFilePath);
        @chmod($tmpFilePath, 0664);
        @unlink($tmpFilePath);
        $this->assertFileNotExists($tmpFilePath);
    }

    /**
     * @param $filePath
     *
     * @return array
     */
    protected function getParserParams($adapterName, $filePath)
    {
        return array(
            __FILE__,
            $adapterName,
            TEST_DATA_PATH . '/tmp.pot',
            '--from-code=UTF-8',
            '-k_',
            '-kgettext',
            '-kgettext_noop',
            $filePath
        );
    }
}
