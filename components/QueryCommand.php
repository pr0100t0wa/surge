<?php


namespace app\components;

/**
 * Class QueryCommand
 * @package common\components
 */
class QueryCommand
{
    /**
     * @param string $table the table that new rows will be inserted into.
     * @param array $columns the column names
     * @param array|\Generator $rows the rows to be batch inserted into the table
     */
    public static function batchInsertOnDuplicateUpdate($table, $columns, $rows)
    {
        $onDuplicateKeyValues = [];
        foreach ($columns as $itemColumn) {
            $column = \Yii::$app->db->getSchema()->quoteColumnName($itemColumn);
            $onDuplicateKeyValues[] = $column . " = VALUES(" . $column . ")";
        }
        $sql = \Yii::$app->db->createCommand()->batchInsert($table, $columns, $rows)->getRawSql();
        $sql .= ' ON DUPLICATE KEY UPDATE ' . implode(', ', $onDuplicateKeyValues);
        return \Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * @param string $table the table that new rows will be inserted into.
     * @param array $columns the column names
     * @param array|\Generator $rows the rows to be batch inserted into the table
     */
    public static function batchInsertIgnore($table, $columns, $rows)
    {
        $sql = \Yii::$app->db->createCommand()->batchInsert($table, $columns, $rows)->getRawSql();
        $sql = str_replace('INSERT INTO', 'INSERT IGNORE', $sql);
        return \Yii::$app->db->createCommand($sql)->execute();
    }
}