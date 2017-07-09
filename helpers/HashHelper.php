<?php

namespace app\helpers;

class HashHelper
{
    /**
     * Generate hash for each file in array $files
     * @param $algo
     * @param $files
     * @return array
     */
    public static function generateHash($algo, $files)
    {
        $hashes = [];
        foreach ($files as $file){
            $hashes[$file] = hash_file($algo, $file);
        }

        return $hashes;
    }

}