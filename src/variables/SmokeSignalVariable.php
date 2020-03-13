<?php
/**
 * Smoke Signal plugin for Craft CMS 3.x
 *
 * Smoke Signal provides a quick way to inform your visitors.
 *
 * @link      https://www.marbles.be/
 * @copyright Copyright (c) 2020 Marbles
 */

namespace marbles\smokesignal\variables;

use marbles\smokesignal\SmokeSignal;

use Craft;

/**
 * Smoke Signal Variable
 *
 * Craft allows plugins to provide their own template variables, accessible from
 * the {{ craft }} global variable (e.g. {{ craft.smokeSignal }}).
 *
 * https://craftcms.com/docs/plugins/variables
 *
 * @author    Marbles
 * @package   SmokeSignal
 * @since     1.0.0
 */
class SmokeSignalVariable
{
    // Public Methods
    // =========================================================================

    /**
     * Whatever you want to output to a Twig template can go into a Variable method.
     * You can have as many variable functions as you want.  From any Twig template,
     * call it like this:
     *
     *     {{ craft.smokeSignal.exampleVariable }}
     *
     * Or, if your variable requires parameters from Twig:
     *
     *     {{ craft.smokeSignal.exampleVariable(twigValue) }}
     *
     * @param null $optional
     * @return string
     */
    public function exampleVariable($optional = null)
    {
        $result = "And away we go to the Twig template...";
        if ($optional) {
            $result = "I'm feeling optional today...";
        }
        return $result;
    }
}
