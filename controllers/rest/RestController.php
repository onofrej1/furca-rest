<?php
namespace app\controllers\rest;

//use yii\rest\ActiveController;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use yii\data\ActiveDataFilter;

class RestController extends ActiveController
{
    public $modelClass = null;
    public $enableCsrfValidation = false;

    public function actions(){
        $actions = parent::actions();
        unset($actions['index']);
        return $actions;
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return array_merge(parent::behaviors(), [
            // For cross-domain AJAX request
            'corsFilter'  => [
                'class' => \yii\filters\Cors::className(),
                'cors'  => [
                    // restrict access to domains:
                    'Origin'                           => ['*'],
                    'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                    'Access-Control-Request-Headers' => ['*'],
                    'Access-Control-Allow-Credentials' => null,
                    'Access-Control-Max-Age' => 86400,
                    'Access-Control-Expose-Headers' => [],
                ],
            ],

        ]);
    }

    public function actionIndex()
    {
        $fields = (new $this->modelClass())->getTableSchema()->getColumnNames();
        $fileName = basename((new \ReflectionClass($this->modelClass))->getShortName());

        $searchModel = './../../models/'.$fileName.'Search.php';
        if (!file_exists($searchModel)) {
          $searchModel = new \yii\base\DynamicModel(['id' => null, 'title' => null]);
          foreach($fields as $field) {
            $column = (new $this->modelClass())->getTableSchema()->getColumn($field);
            $searchModel->addRule($column->name, 'string');
          }
        }

        $filter = new ActiveDataFilter([
          'searchModel' => $searchModel
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
