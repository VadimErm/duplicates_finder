<?php
/**
 * Created by PhpStorm.
 * User: vadim
 * Date: 09.07.17
 * Time: 14:33
 */

namespace app\models;


use yii\base\Model;

class FileInputForm extends Model
{
    public $path;

    public function rules()
    {
       return [
           ['path', 'required'],
           ['path', 'string']
       ];
    }



}