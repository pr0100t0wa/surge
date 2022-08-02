<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use \app\models\Tender;


/* @var $this yii\web\View */
/* @var $searchModel app\models\search\TenderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tenders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tender-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'tender_id',
            [
                'attribute'      => 'description',
                'content' => function($model){
                    /** @var Tender $model */
                    return Html::tag('div', $model->description, ['style' => [
                        'max-width'  => '300px',
                        'max-height' => '300px',
                        'overflow-y' => 'auto',
                    ]]);
                },
            ],
            'amount',
            'date_modified:datetime',
        ],
    ]); ?>


</div>
