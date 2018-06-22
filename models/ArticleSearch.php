<?php
namespace app\models;

use yii\base\Model;

class ArticleSearch extends Model
{
    public $id;
    public $title;

    public function rules()
    {
        return [
            ['id', 'integer'],
            ['title', 'string', 'min' => 2, 'max' => 200],
        ];
    }
}
