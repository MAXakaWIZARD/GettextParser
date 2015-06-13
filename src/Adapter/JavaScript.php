<?php
namespace GettextParser\Adapter;

/**
 * Adapter for JavaScript files processing
 *
 * @package GettextParser\Adapter
 */
class JavaScript extends AbstractAdapter
{
    /**
     * @var array
     */
    protected $patternConfig = array(
        /**
         * @example non-plural calls: _('Text'), _("Text"), _( 'Text' )
         */
        array(
            'pattern' => array(
                "~[^n]+_\([\s]*[\'\"]{1}(.*)[\'\"]{1}[\s]*\)~Uu",
                "~^_\([\s]*[\'\"]{1}(.*)[\'\"]{1}[\s]*\)~Uu"
            ),
            'plural' => false
        ),

        /**
         * @example plural calls: n_( 'country', 'countries', 3 )
         */
        array(
            'pattern' => array(
                "~n_\([\s]*[\'\"]{1}(.*)[\'\"]{1}[\s]*,[\s]*[\'\"]{1}(.*)[\'\"]{1}[\s]*,[\s]*(.*)[\s]*\)~Uu",
                "~n_\([\s]*[\'\"]{1}(.*)[\'\"]{1}[\s]*,[\s]*[\'\"]{1}(.*)[\'\"]{1}[\s]*,[\s]*[\'\"]{1}(.*)[\'\"]{1}[\s]*,[\s]*(.*)[\s]*\)~Uu"
            ),
            'plural' => true
        )
    );
}
