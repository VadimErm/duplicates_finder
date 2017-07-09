<?php


namespace app\models;



use Yii;
use yii\base\ErrorException;
use yii\base\Model;

class Duplicates extends Model
{
    public $files;
    protected $_arrHash;
    protected $_duplicates;


    public function find()
    {

        if($this->files){
            $this->_arrHash = $this->hash();
            asort($this->_arrHash);
            $arrCountValues = array_count_values($this->_arrHash);
           foreach ($arrCountValues as $hash => $count){
                if($count > 1){
                    $this->_duplicates[] = $this->findDuplicates($hash, $count);
                }
            }
            if(!empty($this->_duplicates)){
                return true;
            } else {
                return false;
            }

        } else {
           throw new ErrorException("The 'files' property is not set.");
        }
    }

    protected function hash()
    {
        $hashes = [];
        foreach ($this->files as $file){
            $hashes[$file] = hash_file('sha1', $file);
        }

        return $hashes;
    }

    protected function findDuplicates($currentHash, $count)
    {
        $result = [];
        $trigger = false;
        $i = 0;
        foreach ($this->_arrHash as $path => $hash){
            if($hash == $currentHash && $trigger == false){
                $trigger = true;
            } elseif ($hash == $currentHash && $trigger == true && $i < $count -1 ){
                $result[] = $path;
                $i++;
            }
        }

        return $result;
    }

    public function saveToFile($name)
    {
        $pathToSave = Yii::getAlias('@webroot/files/'.$name.'.txt');
        $str = '';
        if($this->_duplicates){
            foreach ($this->_duplicates as $value){
                foreach ($value as $duplicate){
                    $str .= $duplicate."\n";
                }
            }
        }

        if(file_put_contents($pathToSave, $str))
        {
            return '/files/'.$name.'.txt';

        } else{
            return false;
        }



    }

    public function getDuplicates()
    {
        if($this->_duplicates){
            return $this->_duplicates;
        } else {
            return null;
        }
    }

}