<?php
namespace marbles\smokesignal\elements;

use Craft;
use craft\base\Element;
use craft\base\Field;
use craft\db\Query;
use craft\elements\db\ElementQueryInterface;
use craft\elements\Entry;
use craft\helpers\UrlHelper;
use marbles\smokesignal\elements\db\SignalQuery;
use marbles\smokesignal\SmokeSignal;

/**
 *
 * @property string $namespace
 */
class Signal extends Element
{
    /** @var int */
    public $redirectEntryId;

    /** @var string */
    public $name;
    /** @var string */
    public $handle;
    /** @var string */
    public $description;
    /** @var string */
    public $icon;
    /** @var string */
    public $color;
    /** @var string */
    public $position;
    /** @var string */
    public $link;


    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    /**
     * Use the form handle as the string representation.
     *
     * @return string
     */
    function __toString()
    {
        return Craft::t('smoke-signal', $this->name);
    }

    public static function find(): ElementQueryInterface
    {
        return new SignalQuery(self::class);
    }

    /**
     * @inheritDoc BaseElementModel::isEditable()
     *
     * @return bool
     */
    public function getIsEditable(): bool
    {
        return true;
    }

    /**
     * Returns the element's CP edit URL.
     *
     * @return string|false
     */
    public function getCpEditUrl()
    {
        return UrlHelper::cpUrl('smoke-signal/signals/edit/' . $this->id);
    }

    /**
     * Returns whether this element type has content.
     *
     * @return bool
     */
    public static function hasContent(): bool
    {
        return false;
    }

    /**
     * Returns whether this element type stores data on a per-locale basis.
     *
     * @return bool
     */
    public static function isLocalized(): bool
    {
        return false;
    }

    protected static function defineSources(string $context = null): array
    {
        return [
            [
                'key' => '*',
                'label' => Craft::t('smoke-signal', 'All signals'),
                'criteria' => []
            ],
        ];
    }

    protected static function defineTableAttributes(): array
    {
        return [
            'name' => Craft::t('smoke-signal', 'Name'),
            'handle' => Craft::t('smoke-signal', 'Handle'),
        ];
    }

    protected static function defineSortOptions(): array
    {
        return [
            'name' => Craft::t('smoke-signal', 'Name'),
            'handle' => Craft::t('smoke-signal', 'Handle')
        ];
    }

    protected function tableAttributeHtml(string $attribute): string
    {
        switch ($attribute) {
            case 'handle':
                return '<code>' . $this->handle . '</code>';
                break;

            default:
                return parent::getTableAttributeHtml($attribute);
                break;
        }
    }

    /**
     * Defines which model attributes should be searchable.
     *
     * @return array
     */
    public static function defineSearchableAttributes(): array
    {
        return [
            'name',
            'handle'
        ];
    }

    public function getEditorHtml(): string
    {
        return sprintf('<div class="pane"><a class="btn submit" href="%s" target="_blank">%s</a></div>',
            $this->getCpEditUrl(),
            Craft::t('smoke-signal', 'Edit signal')
        );
    }


    /**
     * Display the form.
     *
     * With this we can display the Form FieldType on a front-end template.
     *
     * @example {{ entry.fieldHandle.first().displayForm() }}
     *
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \yii\base\Exception
     */
    public function displayForm()
    {
        //return SimpleForms::$plugin->formsService->displayForm($this);
    }

    /**
     * @param bool $isNew
     * @throws \yii\db\Exception
     */
    public function afterSave(bool $isNew)
    {
        $command = \Craft::$app->db->createCommand();

        $fields = [
            'name' => $this->name,
            'handle' => $this->handle,
            'description' => $this->description,
            'icon' => $this->icon,
            'color' => $this->color,
            'link' => $this->link,
            'position' => $this->position,
        ];

        if ($isNew) {
                $command->insert('{{%smokesignal_signals}}', array_merge(['id' => $this->id], $fields))->execute();
        } else {
                $command->update('{{%smokesignal_signals}}', $fields, ['id' => $this->id])->execute();
        }

        parent::afterSave($isNew);
    }
}
