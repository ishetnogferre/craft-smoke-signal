<?php
namespace marbles\smokesignal\elements\db;

use craft\db\Query;
use craft\elements\db\ElementQuery;
use craft\helpers\Db;
use ns\prefix\elements\Product;

class SignalQuery extends ElementQuery
{
    public $name;
    public $handle;

    /**
     * @inheritdoc
     */
    public function __construct($elementType, array $config = [])
    {
        // Default orderBy
        if (!isset($config['orderBy'])) {
            $config['orderBy'] = 'smokesignal_signals.name';
        }
        parent::__construct($elementType, $config);
    }

    public function name($value)
    {
        $this->name = $value;

        return $this;
    }

    public function handle($value)
    {
        $this->handle = $value;

        return $this;
    }

    protected function beforePrepare(): bool
    {
        $this->joinElementTable('smokesignal_signals');

        $this->addSelect('smokesignal_signals.id,
                               smokesignal_signals.name,
                               smokesignal_signals.handle,
                               smokesignal_signals.description,
                               smokesignal_signals.icon,
                               smokesignal_signals.color,
                               smokesignal_signals.link,
                               smokesignal_signals.linkEntry,
                               smokesignal_signals.linkText,
                               smokesignal_signals.linkOpen,
                               smokesignal_signals.position,');

        if ($this->handle) {
            $this->subQuery->andWhere(Db::parseParam('smokesignal_signals.handle', $this->handle));
        }
        if ($this->name) {
            $this->subQuery->andWhere(Db::parseParam('smokesignal_signals.name', $this->name));
        }

        return parent::beforePrepare();
    }
}
