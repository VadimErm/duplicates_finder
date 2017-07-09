<?php

namespace app\controllers;


use app\models\Duplicates;
use app\models\FileInputForm;
use Yii;
use yii\base\ErrorException;
use yii\base\InvalidParamException;
use yii\helpers\FileHelper;
use yii\web\Controller;


class SiteController extends Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],

        ];
    }

    /**
     * Duplicates finder form
     * @return string|\yii\web\Response
     */
    public function actionIndex()
    {
        $model = new FileInputForm();
        if ($model->load(Yii::$app->request->post())) {
           $nPath = FileHelper::normalizePath($model->path);
           try{
               $files = FileHelper::findFiles($nPath, ['recursive' =>false]);
                if(!empty($files)){
                    $duplicates = new Duplicates();
                    $duplicates->files = $files;
                   try{
                         if($duplicates->find() && $savedFile = $duplicates->saveToFile(basename($nPath))){

                             return $this->render('index', [
                                 'model' => $model,
                                 'savedFile' => $savedFile
                             ]);

                         } else {
                             Yii::$app->session->setFlash('noDuplicates');
                             return $this->refresh();
                         }

                   } catch (ErrorException $e) {
                        Yii::$app->session->setFlash('error');
                        return $this->refresh();
                    }


                } else {
                    Yii::$app->session->setFlash('dirIsEmpty');
                    return $this->refresh();
                }
           } catch (InvalidParamException $e){
               Yii::$app->session->setFlash('dirNotExist');
               return $this->refresh();
           }

        }

        return $this->render('index', [
            'model' => $model
        ]);
    }
}
