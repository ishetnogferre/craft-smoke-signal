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
     * Get the Plugin's name.
     *
     * @example {{ craft.smokeSignal.name }}
     * @return string
     */
    public function getName()
    {
        return SmokeSignal::$plugin->name;
    }
}
