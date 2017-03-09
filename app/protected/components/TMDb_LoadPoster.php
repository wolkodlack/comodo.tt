<?php
/**
 * Created by IntelliJ IDEA.
 * User: wolkodlack
 * Date: 3/4/17
 * Time: 4:57 AM
 */

class TMDb_LoadPoster {


    public static function load($name) {
        if (empty($name)) {
            return '- none -';
        }
        $url = 'https://image.tmdb.org/t/p/w185/'.$name;
        $storePath = realpath(dirname(__FILE__).'/../../images') .$name;
        if(! file_exists($storePath))
            file_put_contents($storePath, fopen($url, 'r'));

        $localUrl = '/images'. $name;
        $imgHtml = CHtml::image($localUrl);
        return $imgHtml;

    }
}