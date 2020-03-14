<?php

namespace marbles\smokesignal\migrations;

use Craft;
use craft\db\Migration;

class Install extends Migration
{
    // Public Properties
    // =========================================================================

    /**
     * @var string The database driver to use
     */
    public $driver;

    // Public Methods
    // =========================================================================

    /**
     * This method contains the logic to be executed when applying this migration.
     * This method differs from [[up()]] in that the DB logic implemented here will
     * be enclosed within a DB transaction.
     * Child classes may implement this method instead of [[up()]] if the DB logic
     * needs to be within a transaction.
     *
     * @return bool return a false value to indicate the migration fails
     *              and should not proceed further. All other return values mean the migration succeeds.
     */
    public function safeUp()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        if ($this->createTables()) {
            // Refresh the db schema caches
            Craft::$app->db->schema->refresh();
        }

        return true;
    }

    /**
     * This method contains the logic to be executed when removing this migration.
     * This method differs from [[down()]] in that the DB logic implemented here will
     * be enclosed within a DB transaction.
     * Child classes may implement this method instead of [[down()]] if the DB logic
     * needs to be within a transaction.
     *
     * @return bool return a false value to indicate the migration fails
     *              and should not proceed further. All other return values mean the migration succeeds.
     */
    public function safeDown()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        $this->removeTables();

        return true;
    }

    // Protected Methods
    // =========================================================================

    /**
     * Creates the tables needed for the Records used by the plugin.
     *
     * @return bool
     */
    protected function createTables()
    {
        $tablesCreated = false;

        // simple-forms_forms table
        $tableSchema = Craft::$app->db->schema->getTableSchema('{{%smokesignal_signals}}');
        if ($tableSchema === null) {
            $tablesCreated = true;
            $this->createTable(
                '{{%smokesignal_signals}}',
                [
                    'id' => $this->integer()->notNull(),
                    'name' => $this->string()->notNull(),
                    'handle' => $this->string()->notNull(),
                    'description' => $this->string()->notNull(),
                    'icon' => $this->string()->notNull(),
                    'color' => $this->string()->notNull(),
                    'link' => $this->string()->notNull(),
                    'position' => $this->string()->notNull(),
                    'dateCreated' => $this->datetime()->notNull(),
                    'dateUpdated' => $this->datetime()->notNull(),
                    'uid'         => $this->uid(),
                    'PRIMARY KEY(id)',
                ]
            );
        }

        return $tablesCreated;
    }

    /**
     * Creates the foreign keys needed for the Records used by the plugin.
     *
     * @return void
     */
    protected function addForeignKeys()
    {

    }

    /**
     * Populates the DB with the default data.
     *
     * @return void
     */
    protected function insertDefaultData()
    {
    }

    /**
     * Removes the tables needed for the Records used by the plugin.
     *
     * @return void
     */
    protected function removeTables()
    {
        // contactform_submissions table
        $this->dropTableIfExists('{{%smokesignal_signals}}');
    }
}
