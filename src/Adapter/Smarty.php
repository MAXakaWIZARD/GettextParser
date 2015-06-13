<?php
namespace GettextParser\Adapter;

/**
 * Adapter for Smarty files processing
 *
 * @package GettextParser\Adapter
 */
class Smarty extends AbstractAdapter
{
    /**
     * @var array
     */
    protected $patternConfig = array(
        array(
            'pattern' => array(
                /**
                 * @example modifier: {"Text to be localized"|_}
                 */
                "~\{\"([^\"]+)\"\|_([^\}]*)\}~",

                /**
                 * @example block.t: {t}Text to be localized{/t}
                 */
                "~\{t\}([^\{]+)\{/t\}~",

                /**
                 * @example block.t: {t sprintf_args=[]}Text to be localized{/t}
                 */
                "~\{t sprintf_args=[^\}]+\}([^\{]+)\{/t\}~",

                /**
                 * @example gettext function call: {_("Text to be localized")}
                 */
                "~\{_\((?:\"|')([^\)]+)(?:\"|')\)\}~"
            ),
            'plural' => false
        )
    );
}
