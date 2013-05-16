<?php

class GettextParserAdapter_Smarty extends GettextParserAdapter {

    public function __construct($functionsList = "")
    {
        parent::__construct($functionsList);

        //search for non-plural calls: _('Text'), _("Text"), _( 'Text' )
        $this->_patterns[] = new GettextParserPattern("~[^n]+(?:" . implode("|", $this->_functionsList) . ")\([\s]*[\'\"]{1}(.*)[\'\"]{1}[\s]*\)~Uu");
        $this->_patterns[] = new GettextParserPattern("~^(?:" . implode("|", $this->_functionsList) . ")\([\s]*[\'\"]{1}(.*)[\'\"]{1}[\s]*\)~Uu");

        // search for smarty modifier: {"Text to be localized"|_}
        $this->_patterns[] = new GettextParserPattern("~\{\"([^\"]+)\"\|(?:" . implode("|", $this->_functionsList) . ")([^\}]*)\}~");

        //search for smarty modifier: {t}Text to be localized{/t}
        $this->_patterns[] = new GettextParserPattern("~\{t\}([^\{]+)\{/t\}~");
    }

}

?>