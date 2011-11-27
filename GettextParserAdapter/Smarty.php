<?php

class GettextParserAdapter_Smarty extends GettextParserAdapter
{
    public function __construct()
    {
        //add patterns

        // search for smarty modifier: {"Text to be localized"|_}
        $this->_patterns[] = new GettextParserPattern( "~\{\"([^\"]+)\"\|_([^\}]*)\}~" );

        //search for smarty modifier: {t}Text to be localized{/t}
        $this->_patterns[] = new GettextParserPattern( "~\{t\}([^\{]+)\{/t\}~" );
    }
}