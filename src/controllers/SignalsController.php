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
use marbles\smokesignal\elements\Signal;

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
class SignalsController extends Controller
{

    /**
     * Show forms.
     */
    public function actionIndex()
    {
        $variables = [
            'elementType' => Signal::class,
        ];

        return $this->renderTemplate('smoke-signal/signals/index', $variables);
    }

    /**
     * Create or edit a form.
     *
     * @param int|null $signalId
     * @param Signal|null $signal
     * @throws Exception
     */
    public function actionEditSignal(int $signalId = null, Signal $signal = null)
    {
        $variables = [
            '$signalId' => $signalId,
        ];

        // Do we have a form model?
        if (! $signal) {
            // Get form if available
            if ($signalId) {

            }
            else {
                $variables['signal'] = new Signal();
            }
        }

        $this->renderTemplate('smoke-signal/signals/_edit', $variables);
    }

    /**
     * Save a signal.
     *
     * @throws \yii\web\BadRequestHttpException
     * @throws Exception
     * @throws \Throwable
     */
    public function actionSaveSignal()
    {
        $this->requirePostRequest();
        $request = Craft::$app->getRequest();
        $signal = new Signal();

        Craft::dd(SmokeSignal::$plugin->signalsService);

        $signalId = $request->getBodyParam('signalId');
        if ($signalId && $signalId !== 'copy') {
            $signal = SmokeSignal::$plugin->signalsService->getSignalById($formId);

            if (! $signal) {
                throw new Exception(Craft::t('smoke-signal', 'No signal exists with the ID “{id}”.', ['id' => $signalId]));
            }
        }
        // Form attributes
        $signal->name                     = $request->getBodyParam('name');
        $signal->handle                   = $request->getBodyParam('handle');

        // Duplicate form, so the name and handle are taken
        if ($signalId && $signalId === 'copy') {
            SmokeSignal::$plugin->signalsService->getUniqueNameAndHandle($signal);
        }

        // Save form
        if (SmokeSignal::$plugin->signalsService->saveSignal($signal)) {
            Craft::$app->getSession()->setNotice(Craft::t('smoke-signal', 'Signal saved.'));

            $this->redirectToPostedUrl($signal);
        } else {
            Craft::$app->getSession()->setError(Craft::t('smoke-signal', 'Couldn’t save signal.'));

            // Send the form back to the template
            Craft::$app->getUrlManager()->setRouteParams([
                'signal' => $signal
            ]);
        }
    }

    /**
     * Delete a signal.
     */
    public function actionDeleteSignal()
    {
        $this->requirePostRequest();

        // Get form if available
        $signalId = craft()->request->getRequiredPost('signalId');
        $signal = SmokeSignal::$plugin->signalsService->getSignalById($signalId);
        if (! $signal) {
            throw new Exception(Craft::t('No signal exists with the ID “{id}”.', array('id' => $signalId)));
        }

        // Delete form
        if (SmokeSignal::$plugin->signalsService->deleteSignal($signal)) {
            craft()->userSession->setNotice(Craft::t('Signal deleted.'));
        }
        else {
            craft()->userSession->setError(Craft::t('Couldn’t delete signal.'));
        }

        $this->redirectToPostedUrl($signal);
    }
}
