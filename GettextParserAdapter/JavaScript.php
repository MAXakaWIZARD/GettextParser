<?php

class GettextParserAdapter_JavaScript extends GettextParserAdapter
{
    protected $_patterns = array(
      "~_\([\s]*[\'\"]{1}(.*)[\'\"]{1}[\s]*\)~", // search for non-plural calls: _('Text'), _("Text"), _( 'Text' )
      "~n_\([\s]*[\s]*\)~" //search for plural calls: n_( 'country', 'countries', 3 );
    );
}