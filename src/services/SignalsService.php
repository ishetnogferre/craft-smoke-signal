<?php
namespace marbles\smokesignal\services;

use Craft;
use craft\base\Component;
use craft\base\Field;
use craft\helpers\StringHelper;
use Exception;
use marbles\smokesignal\SmokeSignal;
use marbles\smokesignal\elements\db\SignalQuery;
use marbles\smokesignal\elements\Signal;

/**
 * smoke-signals - Signal service
 */
class SignalsService extends Component
{
    /**
     * Returns a criteria model for Signal elements.
     *
     * @param array $attributes
     *
     * @throws Exception
     * @return SignalQuery
     */
    public function getQuery(array $attributes = [])
    {
        return new SignalQuery(Signal::class, $attributes);
    }

    /**
     * Get all signals.
     *
     * @param string $indexBy
     * @return array|\craft\base\ElementInterface[]|null
     * @throws Exception
     */
    public function getAllSignals($indexBy = 'id')
    {
        return $this->getQuery(['orderBy' => 'name', 'indexBy' => $indexBy, 'limit' => null])->all();
    }

    /**
     * Get a signal by its ID.
     *
     * @param int $id
     *
     * @return array|\craft\base\ElementInterface|null
     * @throws Exception
     */
    public function getSignalById($id)
    {
        return $this->getQuery()->id($id)->one();
    }

    /**
     * Get a signal by its handle.
     *
     * @param string $handle
     *
     * @return array|\craft\base\ElementInterface|null
     * @throws Exception
     */
    public function getSignalByHandle($handle)
    {
        return $this->getQuery()->handle($handle)->one();
    }

    /**
     * Save a signal.
     *
     * @param Signal $signal
     *
     * @throws Exception
     * @return bool
     * @throws \Throwable
     */
    public function saveSignal(Signal $signal)
    {
        if (! $signal->hasErrors()) {
            // Save the element!
            if (Craft::$app->getElements()->saveElement($signal)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Delete a signal.
     *
     * @param Signal $signal
     *
     * @throws Exception
     * @return bool
     */
    public function deleteSignal(Signal $signal)
    {
        $transaction = craft()->db->getCurrentTransaction() === null ? craft()->db->beginTransaction() : null;

        try {

            // Delete the element and signal
            craft()->elements->deleteElementById($signal->id);

            if ($transaction !== null) {
                $transaction->commit();
            }

            return true;
        } catch (\Exception $e) {
            if ($transaction !== null) {
                $transaction->rollback();
            }

            throw $e;
        }

        return false;
    }

    /**
     * Get unique name and handle for a signal.
     *
     * @param Signal $signal
     */
    public function getUniqueNameAndHandle(Signal $signal)
    {
        $slugWordSeparator = craft()->config->get('slugWordSeparator');
        $maxSlugIncrement = craft()->config->get('maxSlugIncrement');

        for ($i = 0; $i < $maxSlugIncrement; $i++) {
            $testName = $signal->name;

            if ($i > 0) {
                $testName .= $slugWordSeparator.$i;
            }

            $originalName = $signal->name;
            $originalHandle = $signal->handle;
            $signal->name = $testName;
            $signal->handle = StringHelper::toCamelCase($signal->name);

            $totalSignals = craft()->db->createCommand()
                ->select('count(id)')
                ->from('smokesignals_signals')
                ->where('name=:name AND handle=:handle', array(
                    ':name' => $signal->name,
                    ':handle' => $signal->handle,
                ))
                ->queryScalar();

            if ($totalSignals ==  0) {
                return;
            }
            else {
                $signal->name = $originalName;
                $signal->handle = $originalHandle;
            }
        }

        throw new Exception(Craft::t('Could not find a unique name and handle for this signal.'));
    }

    /**
     * Display a signal.
     *
     * @param Signal signal
     *
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \yii\base\Exception
     */
    public function displaySignal(Signal $signal)
    {

        // Set namespace
        $namespace = 'signal_'.StringHelper::randomString(10);
        Craft::$app->getView()->setNamespace($namespace);


        // Build tab HTML
        $variables = [
            'signal'    => $signal
        ];
        $html = Craft::$app->getView()->renderTemplate('signals/templates/signal', $variables);
        // Reset namespace
        Craft::$app->getView()->setNamespace(null);

        // Parse form
        return new \Twig_Markup($html, Craft::$app->getView()->getTwig()->getCharset());
    }
}
