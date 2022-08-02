<?php

namespace app\models;

use common\components\behaviors\ErrorBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "tender".
 *
 * @property string $id
 * @property string|null $tender_id
 * @property string|null $description
 * @property float|null $amount
 * @property string|null $date_modified
 */
class Tender extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tender';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['description'], 'string'],
            [['amount'], 'number'],
            [['date_modified'], 'safe'],
            [['id'], 'string', 'max' => 32],
            [['tender_id'], 'string', 'max' => 22],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'tender_id'     => 'Tender ID',
            'description'   => 'Description',
            'amount'        => 'Amount',
            'date_modified' => 'Date Modified',
        ];
    }

}
