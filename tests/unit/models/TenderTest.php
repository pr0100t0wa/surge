<?php
namespace tests\unit\models;

use app\models\Tender;
use yii\helpers\ArrayHelper;

class TenderTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    public function testCreate()
    {
        $tenderInfo = \Yii::$app->tender->getTenderInfo('c520b5b093d64e98bd5cc08287e97bba');
        $this->assertEquals(ArrayHelper::getValue($tenderInfo, 'id'), 'c520b5b093d64e98bd5cc08287e97bba');

        $tender = new Tender();
        $tender->setAttributes([
            'id'            => ArrayHelper::getValue($tenderInfo, 'id'),
            'tender_id'     => ArrayHelper::getValue($tenderInfo, 'tenderID'),
            'description'   => ArrayHelper::getValue($tenderInfo, 'description'),
            'amount'        => ArrayHelper::getValue($tenderInfo, 'value.amount'),
            'date_modified' => ArrayHelper::getValue($tenderInfo, 'dateModified'),
        ]);

        $this->assertTrue($tender->validate());
        $this->assertTrue($tender->save());
    }

    public function testDelete()
    {
        $tenderInfo = \Yii::$app->tender->getTenderInfo('c520b5b093d64e98bd5cc08287e97bba');
        $this->assertNotEmpty($tenderInfo);

        $tender = new Tender();
        $tender->setAttributes([
            'id'            => ArrayHelper::getValue($tenderInfo, 'id'),
            'tender_id'     => ArrayHelper::getValue($tenderInfo, 'tenderID'),
            'description'   => ArrayHelper::getValue($tenderInfo, 'description'),
            'amount'        => ArrayHelper::getValue($tenderInfo, 'value.amount'),
            'date_modified' => ArrayHelper::getValue($tenderInfo, 'dateModified'),
        ]);
        $tender->save();

        $this->assertEquals(1, $tender->delete());
    }

    public function testGetList()
    {
        $rowsCount = 50;
        \Yii::$app->tender->limitPerPage = $rowsCount;
        $tenders = \Yii::$app->tender->getTenders();
        $this->assertEquals($rowsCount, count($tenders));
    }
}