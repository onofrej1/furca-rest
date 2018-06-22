<?php
namespace app\controllers;

use yii\rest\ActiveController;
use yii\rest\Controller;
use app\models\Page;
use yii\data\ActiveDataProvider;
use yii\data\ActiveDataFilter;

class PageController extends RestController
{
    public $modelClass = 'app\models\Page';

}
