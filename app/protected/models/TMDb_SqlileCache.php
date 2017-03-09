<?php

/**
 * Created by IntelliJ IDEA.
 * User: wolkodlack
 * Date: 3/6/17
 * Time: 9:08 PM
 */
class TMDb_SqlileCache extends CActiveRecord{
    public $id;
    public $title='';
    public $original_title='';
    public $release_date='';
    public $runtime='';
    public $overview='';
    public $genres='';
    public $poster_path='';

    protected $_fieldsForSave = [
        'title',
        'original_title',
        'release_date',
        'runtime',
        'overview',
        'genres',
        'poster_path',
    ];

    /**
     * Primary Key name getter override
     * @return string
     */
    public function primaryKey() {
        return 'id';
    }

    /**
     * Tabe Name getter override
     * @return string
     */
    public function tableName() {
        return 'movie_info';
    }

    /**
     * Returns AR
     * @param string $className
     * @return mixed|static
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * Populates AR object with data is expected to be cached.
     * @param $id
     * @param $dataIn
     */
    public function populateCacheData($id, $dataIn) {
        $fields = $this->_fieldsForSave;
        $fields = array_intersect_key($fields, array_keys($dataIn) );


        $storeData = array_intersect_key(
            $dataIn,
            array_flip($fields)
        );

        $storeData['genres'] = serialize($storeData['genres']);
        if(!isset($storeData['poster_path'])) {
            $storeData['poster_path'] = '';
        };


        $this->id = $id;
        foreach($fields as $key) {
            $this->{$key} = $storeData[$key];
        }

        $this->save(false);
    }

}