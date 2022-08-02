<?php
namespace app\components;

use GuzzleHttp\ClientInterface;
use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * Upload tenders data from openprocurement API
 * Class TenderComponent
 * @package app\components
 */
class TenderComponent extends Component
{
    /**
     * @var array
     */
    public $config;

    /**
     * Results count for one load data iteration
     * @var int
     */
    public $limitPerPage;

    /**
     * Value for pagination data
     * @var null|int
     */
    protected $offset = null;

    /**
     * @var ClientInterface
     */
    protected $client;

    /** @inheritDoc */
    public function init()
    {
        $this->client = new $this->config['clientClass'](['base_uri' => $this->config['baseUri']]);
        $this->limitPerPage = ArrayHelper::getValue($this->config, 'limitPerPage', $this->limitPerPage);
    }

    /**
     * Fetch list of tenders from api
     * @return array
     */
    public function getTenders()
    {
        try {
            $path = 'tenders?descending=1&limit=' . $this->limitPerPage . ($this->offset ? '&offset=' . $this->offset : '');
            $response = $this->client->request('GET', $path);
            $data = Json::decode($response->getBody()->getContents());
            $this->offset = ArrayHelper::getValue($data, 'next_page.offset');
        }catch (\Exception $e){
            Yii::error(sprintf('Can not load tenders with path "%s"', $path), 'tenders-load');
            Yii::error($e->getMessage(), 'tenders-load');
            $data = [];
        }
        return ArrayHelper::getValue($data, 'data', []);
    }

    /**
     * Fetch data for single tender by provided identifier
     * @param string $id
     * @return array
     */
    public function getTenderInfo($id)
    {
        try {
            $response = $this->client->request('GET', 'tenders/' . $id);
            $data = Json::decode($response->getBody()->getContents());
        }catch (\Exception $e){
            Yii::error(sprintf('Can not load data for tender %s', $id), 'tenders-load');
            Yii::error($e->getMessage(), 'tenders-load');
            $data = [];
        }
        return ArrayHelper::getValue($data, 'data', []);
    }
}