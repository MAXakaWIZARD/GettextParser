<?php

class GettextParserAdapter_Smarty extends GettextParserAdapter {

    public function __construct($functionsList = "") {
        parent::__construct($functionsList);
        // search for smarty modifier: {"Text to be localized"|_}
        $this->_patterns[] = new GettextParserPattern("~\{\"([^\"]+)\"\|_([^\}]*)\}~");

        //search for smarty modifier: {t}Text to be localized{/t}
        $this->_patterns[] = new GettextParserPattern("~\{t\}([^\{]+)\{/t\}~");

        // search for smarty modifier: {_("Text to be localized")} and custom functions
        $this->_patterns[] = new GettextParserPattern("~\{(?:" . implode("|", $this->_functionsList) . ")\((?:\"|')([^\)]+)(?:\"|')\)\}~");
    }

}