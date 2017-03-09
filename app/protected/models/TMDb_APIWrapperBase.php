<?php


abstract
class TMDb_APIWrapperBase extends CModel {
    protected $_apiKey;

    /**
     * Example request:
    https://api.themoviedb.org/3/authentication/guest_session/new?api_key=9de93e8bdd27809d1f4f209b1be14c9e
     */
    const HOST_NAME='api.themoviedb.org';
    const PROTO='https';

    public function attributeNames() {
        return ['name'];
    }

    protected function __construct($apiKey) {
        $this->_apiKey = $apiKey;

    }

    /**
     * Curl wrapper-method
     * @param string $method
     * @param string $path
     * @param bool $data
     * @return array
     */
    protected function _call($method='GET', $path='', $args=false, $data=false, $headers=false) {
        $mapMethod = [
            'GET'   => CURLOPT_HTTPGET,
            'PUT'   => CURLOPT_PUT,
            'POST'  => CURLOPT_POST,
        ];

        $urlBasePattern = '%s://%s/%s';
//        $urlBasePattern .= '###';     # WD: For flow-check purpose
        $url = sprintf($urlBasePattern, self::PROTO, self::HOST_NAME, $path );
        $curl = curl_init();
        if($args)
            $url = sprintf("%s?%s", $url, http_build_query($args));
        switch ($method) {
            case "POST":
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            default:
                curl_setopt($curl, $mapMethod[$method], true);
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        if ($headers) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }

        $curl_response = curl_exec($curl);
        curl_close($curl);

        $ret = json_decode($curl_response, true);

        return $this->_checkResponce($path, $ret);
    }

    abstract protected function _checkResponce($path, $ret);

    /**
     * @param $path
     * @param $data
     * @return array
     */
    public function get($path, $data) {
        return $this->_call('GET', $path, $data);
    }

    /**
     * @param $path
     * @param $args
     * @param $data
     * @param $headers
     * @return mixed
     */
    public function post($path, $args, $data, $headers) {
        return  $this->_call('POST', $path, $args, $data, $headers);
    }

}