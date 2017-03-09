<?php

/**
 * Created by IntelliJ IDEA.
 * User: wolkodlack
 * Date: 3/3/17
 * Time: 9:01 PM
 */
class TMDb_APIWrapper extends TMDb_APIWrapperBase {
    protected static $_instance=[];
    public $name = 'name';
    public $address = 'address';

    const GUEST_LOGIN       = '3/authentication/guest_session/new';
    const DISCOVER_MOVIE    = '3/discover/movie';
    const MOVIE_INFO        = '3/movie';
    const GUEST_SESSION     = '3/authentication/guest_session/new';
    const MOVIE_RATING      = '3/movie/%s/rating';




    /**
     * Clients loader
     * @param string $apiKey
     * @return static
     */
    public static function instance($apiKey='') {
        if(! isset(self::$_instance[$apiKey] ) ) {
            static::$_instance[$apiKey] = new static($apiKey);
        }

        return static::$_instance[$apiKey];
    }


    /**
     * API response checker
     * @param $path
     * @param $data
     * @return mixed
     */
    protected function _checkResponce($path, $data) {
        if($path === self::GUEST_LOGIN )
            return $data;

        if(!isset($data))
            $this->doGoLogin();
        if(isset($data['success']) and $data['success'])
            return $data;

        if(isset($data['status_code'])) {
            switch ($data['status_code']){
                case 0: break;
                default:
                    # Something going wrong
                    $this->doGoLogin();
            }
        }

        return $data;
    }

    /**
     * Redirects to Login page
     */
    protected function doGoLogin() {
        $url = Yii::app()->createUrl('site/login');
        Yii::app()->request->redirect($url);
    }


    /**
     * Logins user by api-key
     * @return array
     */
    public function guestLogin() {
        $path = self::GUEST_LOGIN;
        $params = [
            'api_key' => $this->_apiKey,
        ];
        $ret = $this->get($path, $params);

        return $ret;
    }

    /**
     * Loads session data
     * @return array
     */
    public function getSession() {
        $path = self::GUEST_SESSION;
        $params = [
            'api_key' => $this->_apiKey,
            'request_token' => $this->_apiKey,
        ];
        $ret = $this->get($path, $params);

        return $ret;
    }

    /**
     * Loads detailed movie information
     * @param $listType
     * @param int $page
     * @return TMDb_DataProvider
     */
    public function discoverMovie($listType, $page=0) {
        $path = self::DISCOVER_MOVIE;
        $params = [
            'api_key'               => $this->_apiKey,
            'certification_country' => 'US',
            'certification.lte'     => 'G',
            'sort_by'               => 'popularity.desc',
            'page'                  => $page,
        ];
        if ($listType === 'newest') {
            $params['primary_release_date.gte'] = date('Y-m-d', strtotime('-2 months'));
        }

        $ret = $this->get($path, $params);

        $pages = new CPagination($ret["total_results"]);
        $pages->pageSize=20;

        $provider = new TMDb_DataProvider($ret['results'], [
            'pagination'    => $pages,
            'totalItemCount' => isset($ret["total_results"])?$ret["total_results"]:0,
        ]);

        return $provider;
    }

    /**
     * Loads movie information data
     * @param $id
     * @return array
     */
    public function getMovieInfo($id) {
        $cacheData = TMDb_SqlileCache::model()->findByPk($id);
        $ret = (array) $cacheData;

        if(!isset($cacheData) ) {
            $path = sprintf( self::MOVIE_INFO. '/%s', $id);
            $params = ['api_key' => $this->_apiKey];
            $ret = $this->get($path, $params);
            $m = new TMDb_SqlileCache();
            $m->populateCacheData($id, $ret);
        }
        else {
            $ret['genres'] = unserialize($cacheData['genres']);
        }

        return $ret;
    }

    /**
     * @param $id
     * @param $rating
     * @return bool
     */
    public function setMovieRating($id, $rating) {
        $idSession = '';
        $ret = $this->getSession();
        if (isset($ret['guest_session_id']) ) {
            $idSession = $ret['guest_session_id'];
        }
        $path = sprintf(self::MOVIE_RATING, $id);
        $params = [
            'guest_session_id' => $idSession,
            'api_key' => $this->_apiKey,
        ];
        $data = json_encode(["value"=> floatval($rating)]);
        $headers =[
            'Content-Type: application/json;charset=utf-8',
        ];
        $ret = $this->post($path, $params, $data, $headers);
        if(isset($ret['status_message']) and 'Success.' === $ret['status_message'])
            return true;

        return false;
    }


}

