<?php
namespace app\controllers;

//use yii\rest\ActiveController;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use yii\data\ActiveDataFilter;

class RestController extends Controller
{
    public $modelClass = null;

    public function actionIndex()
    {
        $filter = new ActiveDataFilter([
          'searchModel' => $this->modelClass.'Search'
        ]);

        $filterCondition = null;

        if ($filter->load(\Yii::$app->request->get())) {
            $filterCondition = $filter->build();
            if ($filterCondition === false) {
                return $filter;
            }
        }

        $query = $this->modelClass::find();
        if ($filterCondition !== null) {
            $query->andWhere($filterCondition);
        }
        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }
}
