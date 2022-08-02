<?php

namespace app\commands;

use app\models\Tender;
use app\components\QueryCommand;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Yii;
use yii\console\Controller;
use yii\helpers\ArrayHelper;

/**
 * Upload tenders data from openprocurement and store to `tender` table
 * Class TenderController
 * @package app\commands
 * @note
 */
class TenderController extends Controller
{
    /**
     * @var int
     */
    protected $savedRows = 0;

    /**
     * @var ClientInterface
     */
    protected $client;

    /** @inheritDoc */
    public function beforeAction($action)
    {
        $this->client = new Client(['base_uri' => 'https://public.api.openprocurement.org/api/0/']);
        return parent::beforeAction($action);
    }

    /**
     * @param int $pagesCount
     * @param int $limitPerPage
     * @example `php yii tender`
     */
    public function actionIndex($pagesCount = 10, $limitPerPage = 20)
    {
        Yii::$app->tender->limitPerPage = $limitPerPage;

        Yii::info(sprintf('Start tenders uploading. pagesCount = %d, limitPerPage = %d', $pagesCount, $limitPerPage), 'tenders-load');

        for ($i = 0; $i < $pagesCount; $i++){
            $this->uploadTenders();
        }

        Yii::info(sprintf('Total saved rows = %d', $this->savedRows), 'tenders-load');

    }

    /**
     * Load tenders data, formatting results and store to DB
     */
    protected function uploadTenders()
    {
        $insertData = [];
        foreach (Yii::$app->tender->getTenders() as $tender){
            $tenderId = ArrayHelper::getValue($tender, 'id');
            if ($tenderId && ($tenderInfo = Yii::$app->tender->getTenderInfo($tenderId))){
                $insertData[] = [
                    'id'            => ArrayHelper::getValue($tenderInfo, 'id'),
                    'tender_id'     => ArrayHelper::getValue($tenderInfo, 'tenderID'),
                    'description'   => ArrayHelper::getValue($tenderInfo, 'description'),
                    'amount'        => ArrayHelper::getValue($tenderInfo, 'value.amount'),
                    'date_modified' => ArrayHelper::getValue($tenderInfo, 'dateModified'),
                ];
            }
            //save part of the results to avoid database overload
            if (count($insertData) >= 100){
                $this->savedRows += $this->saveData($insertData);
                $insertData = [];
                Yii::info(sprintf('Saved rows = %d', $this->savedRows), 'tenders-load');
            }
        }
        //save the rest of the results
        if ($insertData){
            $this->savedRows += $this->saveData($insertData);
        }
    }

    /**
     * Batch save for collected results
     * avoid overhead for models load and single record saving
     * @param array $data
     * @return int
     */
    protected function saveData($data)
    {
        $rowsSaved = 0;
        try {
            $rowsSaved = QueryCommand::batchInsertOnDuplicateUpdate(Tender::tableName(), array_keys($data[0]), $data);
        }catch (\Exception $e){
            Yii::error($e->getMessage(), 'tenders-load');
        }

        return $rowsSaved;
    }
}
