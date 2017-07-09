<?php


namespace app\models;



use app\helpers\HashHelper;
use Yii;
use yii\base\ErrorException;
use yii\base\Model;

class Duplicates extends Model
{
    public $files;
    protected $_arrHash;
    protected $_duplicates;

    /**
     * Find duplicates
     * @return bool
     * @throws ErrorException
     */
    public function find()
    {

        if($this->files){
            $this->_arrHash = HashHelper::generateHash('sha1',$this->files); //Generate hash for each file
            asort($this->_arrHash);
            $arrCountValues = array_count_values($this->_arrHash);
            //Filter if in array exist duplicates
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

    /**
     * Find duplicates for current file
     * @param $currentHash
     * @param $count
     * @return array
     */
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

    /**
     * Save results to file
     * @param $name
     * @return bool|string
     */
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

    /**
     * Get duplicates
     * @return null
     */
    public function getDuplicates()
    {
        if($this->_duplicates){
            return $this->_duplicates;
        } else {
            return null;
        }
    }

}