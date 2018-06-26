<?php

namespace app\controllers;

use yii\web\Controller;
use Yii;

class TestController extends Controller
{

public $layout = 'test';
  /**
   * Displays about page.
   *
   * @return string
   */
  public function actionTest()
  {
    $modelDir = "../models";

    $connection = Yii::$app->db;
    $dbSchema = $connection->schema;

    $tables = $dbSchema->tableNames;
    foreach($tables as $table) {
      $class = $this->camelize($table);
      $className = 'app\\models\\'.$class;
      echo $class.'<br>';

      $template = \app\TemplateParser::parse('ActiveRecord.txt', [
        'class' => $class,
        'namespace' => 'app\\models',
        'table' => $table
      ]);

      $file = fopen($modelDir.'/'.$class.".php", "w") or die("Unable to open file!");
      fwrite($file, $template);
      fclose($file);

      $template = \app\TemplateParser::parse('RestController.txt', [
        'class' => $class.'Controller',
        'model' => $className
      ]);

      $appDir = Yii::getAlias('@app');
      $file = fopen($appDir.'/controllers/rest/'.$class."Controller.php", "w") or die("Unable to open file!");
      fwrite($file, $template);
      fclose($file);
    }

    return $this->render('say', ['message' => '']);
  }

  protected function camelize($input, $separator = '_')
  {
    return str_replace($separator, '', ucwords($input, $separator));
  }

    // ...existing code...

    public function actionSay($message = 'Hello')
    {
        return $this->render('say', ['message' => $message]);
    }
}
