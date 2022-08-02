<?php

namespace app\controllers;

use app\models\search\TenderSearch;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * TenderController implements the CRUD actions for Tender model.
 */
class TenderController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Tender models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TenderSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
