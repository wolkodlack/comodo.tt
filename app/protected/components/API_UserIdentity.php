<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class API_UserIdentity extends CUserIdentity {
    protected $_apiKey;


    /**
     * @var TMDb_APIWrapper
     */
    protected $_apiWrapper;

    /**
     * UserIdentity constructor.
     * @param string $api_key
     */
    public function __construct($api_key) {
        $this->_apiKey = $api_key;
        $this->_apiWrapper = TMDb_APIWrapper::instance($api_key);
        parent::__construct('GuestUser', '--');
    }

    /**
     * @return string
     */
    public function getId() {
        return $this->_apiKey;
    }

    /**
     * Authenticates a user.
     * @return boolean whether authentication succeeds.
     */
    public function authenticate() {
        $ret = $this->_apiWrapper->guestLogin();

        if(!isset($ret['success']) or true!==$ret['success'])
            $this->errorCode = self::ERROR_UNKNOWN_IDENTITY;
        else
            $this->errorCode = self::ERROR_NONE;
        return !$this->errorCode;
    }
}
