<?php
/**
 * Smoke Signal plugin for Craft CMS 3.x
 *
 * Smoke Signal provides a quick way to inform your visitors.
 *
 * @link      https://www.marbles.be/
 * @copyright Copyright (c) 2020 Marbles
 */

namespace marbles\smokesignal;

use marbles\smokesignal\services\SignalService;
use marbles\smokesignal\variables\SmokeSignalVariable;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\web\UrlManager;
use craft\web\twig\variables\CraftVariable;
use craft\events\RegisterUrlRulesEvent;
use marbles\smokesignal\models\Settings;

use yii\base\Event;

/**
 * Class SmokeSignal
 *
 *
 * @package   marbles\smokesignal
 *
 * @property  SignalService $signalService
 * @property  Settings $settings
 * @method    Settings getSettings()
 */
class SmokeSignal extends Plugin
{
    /**
     * @var SmokeSignal
     */
    public static $plugin;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        // Register our site routes
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['siteActionTrigger1'] = 'smoke-signal/smoke-signal';
            }
        );

        Craft::dd($this->signalsService);

        // Register our CP routes
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['smoke-signal/signals'] = 'smoke-signal/signals/index';
                $event->rules['smoke-signal/signals/new'] =  'smoke-signal/signals/edit-signal';
                $event->rules['smoke-signal/signals/edit/<signalId:\d+>'] = 'smoke-signal/signals/edit-signal';
            }
        );

        // Register our variables
        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('smokeSignal', SmokeSignalVariable::class);
            }
        );

        // Do something after we're installed
        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                    // We were just installed
                }
            }
        );

/**
 * Logging in Craft involves using one of the following methods:
 *
 * Craft::trace(): record a message to trace how a piece of code runs. This is mainly for development use.
 * Craft::info(): record a message that conveys some useful information.
 * Craft::warning(): record a warning message that indicates something unexpected has happened.
 * Craft::error(): record a fatal error that should be investigated as soon as possible.
 *
 * Unless `devMode` is on, only Craft::warning() & Craft::error() will log to `craft/storage/logs/web.log`
 *
 * It's recommended that you pass in the magic constant `__METHOD__` as the second parameter, which sets
 * the category to the method (prefixed with the fully qualified class name) where the constant appears.
 *
 * To enable the Yii debug toolbar, go to your user account in the AdminCP and check the
 * [] Show the debug toolbar on the front end & [] Show the debug toolbar on the Control Panel
 *
 * http://www.yiiframework.com/doc-2.0/guide-runtime-logging.html
 */
        Craft::info(
            Craft::t(
                'smoke-signal',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

    /**
     * Creates and returns the model used to store the plugin’s settings.
     *
     * @return \craft\base\Model|null
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    public function getCpNavItem()
    {
        $navItem = parent::getCpNavItem();

        $navItem['label'] = 'Smoke Signal';

        $navItem['subnav'] = [
            'general' => [
                'label' => Craft::t('smoke-signal', 'General'),
                'url' => 'smoke-signal'
            ],
            'connect' => [
                'label' => Craft::t('smoke-signal', 'Signals'),
                'url' => 'smoke-signal/signals'
            ],
            'settings' => [
                'label' => Craft::t('smoke-signal', 'Settings'),
                'url' => 'smoke-signal/settings'
            ],
        ];

        return $navItem;
    }

    /**
     * Returns the rendered settings HTML, which will be inserted into the content
     * block on the settings page.
     *
     * @return string The rendered settings HTML
     */
    protected function settingsHtml(): string
    {
        return Craft::$app->view->renderTemplate(
            'smoke-signal/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }
}
