<?php

class GettextParserAdapter_Smarty extends GettextParserAdapter
{
    protected $_patterns = array(
      "~\{\"([^\"]+)\"\|_([^\}]*)\}~",  // search for smarty modifier: {"Text to be localized"|_}
      "~\{t\}([^\{]+)\{/t\}~"           // search for smarty modifier: {t}Text to be localized{/t}
    ); 
}