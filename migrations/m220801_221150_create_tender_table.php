<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tender}}`.
 */
class m220801_221150_create_tender_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            CREATE TABLE IF NOT EXISTS tender (
              id varchar(32) NOT NULL,
              tender_id varchar(22) DEFAULT NULL,
              description text DEFAULT NULL,
              amount decimal(12, 2) UNSIGNED DEFAULT NULL,
              date_modified timestamp NULL DEFAULT NULL,
              PRIMARY KEY (id)
            );
        ");

//        $this->createTable('tender', [
//            'id' => $this->string(32)->notNull(),
//            'tender_id' => $this->string(22),
//            'description' => $this->text(),
//            'amount' => $this->decimal(12, 2),
//            'date_modified' => $this->timestamp(),
//        ]);
//
//        $this->addPrimaryKey('tender_pk', 'tender', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->execute("DROP TABLE IF EXISTS tender;");
//        $this->dropTable('tender');
    }
}
