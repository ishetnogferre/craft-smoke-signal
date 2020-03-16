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
use marbles\smokesignal\elements\Signal;

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

    public function displaySignal(Signal $signal = null)
    {
        if (empty($signal)) {
            $signal = Signal::find()->one();
        }

        return SmokeSignal::$plugin->signalsService->displaySignal($signal);
    }

    public function getColor($color) {
        $hex = str_replace("#", "", $color);
        $r = hexdec($hex[0] . $hex[1]);
        $g = hexdec($hex[2] . $hex[3]);
        $b = hexdec($hex[4] . $hex[5]);

        if($this->lightness($r, $g, $b) >= .8) {
            return 'color-dark';
        } else {
            return 'color-light';
        }
    }

    protected function lightness($R = 255, $G = 255, $B = 255) {
        return (max($R, $G, $B) + min($R, $G, $B)) / 510.0; // HSL algorithm
    }
}
