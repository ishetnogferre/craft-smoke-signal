<?php
/**
 * Smoke Signal plugin for Craft CMS 3.x
 *
 * Smoke Signal provides a quick way to inform your visitors.
 *
 * @link      https://www.marbles.be/
 * @copyright Copyright (c) 2020 Marbles
 */

namespace marbles\smokesignal\controllers;

use marbles\smokesignal\SmokeSignal;

use Craft;
use craft\web\Controller;

/**
 * SmokeSignalController Controller
 *
 * Generally speaking, controllers are the middlemen between the front end of
 * the CP/website and your plugin’s services. They contain action methods which
 * handle individual tasks.
 *
 * A common pattern used throughout Craft involves a controller action gathering
 * post data, saving it on a model, passing the model off to a service, and then
 * responding to the request appropriately depending on the service method’s response.
 *
 * Action methods begin with the prefix “action”, followed by a description of what
 * the method does (for example, actionSaveIngredient()).
 *
 * https://craftcms.com/docs/plugins/controllers
 *
 * @author    Marbles
 * @package   SmokeSignal
 * @since     1.0.0
 */
class SmokeSignalControllerController extends Controller
{

    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected $allowAnonymous = ['index', 'do-something'];

    // Public Methods
    // =========================================================================

    /**
     * Handle a request going to our plugin's index action URL,
     * e.g.: actions/smoke-signal/smoke-signal-controller
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $result = 'Welcome to the SmokeSignalControllerController actionIndex() method';

        return $result;
    }

    /**
     * Handle a request going to our plugin's actionDoSomething URL,
     * e.g.: actions/smoke-signal/smoke-signal-controller/do-something
     *
     * @return mixed
     */
    public function actionDoSomething()
    {
        $result = 'Welcome to the SmokeSignalControllerController actionDoSomething() method';

        return $result;
    }
}
