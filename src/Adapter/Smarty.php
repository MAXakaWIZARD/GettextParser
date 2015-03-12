<?php
namespace GettextParser\Adapter;

use GettextParser\Pattern;

class Smarty extends AbstractAdapter
{
    public function __construct()
    {
        //add patterns

        // search for smarty modifier: {"Text to be localized"|_}
        $this->patterns[] = new Pattern("~\{\"([^\"]+)\"\|_([^\}]*)\}~");

        //search for block.t: {t}Text to be localized{/t}
        $this->patterns[] = new Pattern("~\{t\}([^\{]+)\{/t\}~");

        //search for block.t: {t sprintf_args=[]}Text to be localized{/t}
        $this->patterns[] = new Pattern("~\{t sprintf_args=[^\}]+\}([^\{]+)\{/t\}~");

        // search for gettext function call: {_("Text to be localized")}
        $this->patterns[] = new Pattern("~\{_\((?:\"|')([^\)]+)(?:\"|')\)\}~");
    }
}
