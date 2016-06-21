<?php

/**
 * Provides access to the QIHOO360 Platform.  This class provides
 * a majority of the functionality needed.
 */
class Qihoo_OAuth2
{

    private $_clientId = ""; // api key
    private $_clientSecret = ""; // app secret

    // Set up the API root URL.

    const HOST = 'https://openapi.360.cn';
    const AUTHORIZE_URL = 'https://openapi.360.cn/oauth2/authorize';
    const ACESSTOKEN_URL = 'https://openapi.360.cn/oauth2/access_token';
    const SCOPE_BASIC = 'basic';
    const REDIRECT_URL = 'oob';

    /**
     *
     * @var Qihoo_Http
     */
    private $_http;

    /**
     *
     * @var Qihoo_Logger_Base 
     */
    private $_logger = false;
    private $_scope;

    public function __construct($clientId, $clientSecret, $scope)
    {
        $this->_http = new Qihoo_Util();

        if (empty($clientId)) {
            throw new Qihoo_Exception(Qihoo_Exception::CODE_NO_APPKEY);
        }
        if (empty($clientSecret)) {
            throw new Qihoo_Exception(Qihoo_Exception::CODE_NO_SECRET);
        }
        $this->_clientId = $clientId;
        $this->_clientSecret = $clientSecret;
        if (empty($scope)) {
            $scope = self::SCOPE_BASIC;
        }
        $this->_scope = $scope;
    }

    /**
     * ͨ��codeͬʱ��ȡtoken(����access_token, fresh_token)���û���Ϣ
     * @param String $code
     * @return array
     */
    public function getInfoByCode($code)
    {
        $token = $this->getAccessTokenByCode($code);
        $user = $this->userMe($token['access_token']);
        return array(
            'token' => $token,
            'user' => $user,
        );
    }

    /**
     * �򿪵���
     */
    public function setLogger($logger)
    {
        $this->_logger = $logger;
    }

    /**
     * ͨ��code����ȡaccess_token�Լ�refresh_token
     *
     * @param string $code     Authorized Code get by send HTTP Authorize request.
     *
     * @return a new access token and refresh token.
     */
    public function getAccessTokenByCode($code, $redirectUri = null)
    {
        if ($redirectUri === null) {
            $redirectUri = self::REDIRECT_URL;
        }
        $data = array(
            'grant_type' => "authorization_code",
            'code' => $code,
            'client_id' => $this->_clientId,
            'client_secret' => $this->_clientSecret,
            'redirect_uri' => $redirectUri,
            'scope' => $this->_scope,
        );

        return $this->_request(self::ACESSTOKEN_URL, $data);
    }

    /**
     * ����360�ӿ�
     * @param type $url
     * @param type $data
     * @param type $decode
     * @return type
     * @throws Qihoo_Exception
     */
    private function _request($url, $data, $decode = true)
    {
        $debugUrl = $url . '?' . http_build_query($data);
        $this->_debug(__METHOD__, "���� $debugUrl");
        $jsonStr = Qihoo_Util::request($url, Qihoo_Util::METHOD_GET, $data);

        $err = Qihoo_Util::getError();
        if ($err) {
            $errMsg = "����:{$err['error']}({$err['errno']})";
            $this->_debug(__METHOD__, $errMsg);
            throw new Qihoo_Exception(Qihoo_Exception::CODE_NET_ERROR, $errMsg . "\r\n" . $url);
        }

        if (empty($jsonStr)) {
            $this->_debug(__METHOD__, "����$debugUrl �����˿��ַ���");
            throw new Qihoo_Exception(Qihoo_Exception::CODE_NET_ERROR, $debugUrl);
        }
        $this->_debug(__METHOD__, "����$debugUrl ������" . $jsonStr);
        if (!$decode) {
            return $jsonStr;
        }

        $response = json_decode($jsonStr, true);
        if (empty($response)) {
            $this->_debug(__METHOD__, "json_decodeʧ�ܣ�ԭ��Ϊ$jsonStr");
            throw new Qihoo_Exception(Qihoo_Exception::CODE_JSON_ERROR, $jsonStr);
        }
        $this->_debug(__METHOD__, "����{$debugUrl} json��ѹ����Ϊ" . var_export($response, 1));
        if (!empty($response['error_code'])) {
            $this->_debug(__METHOD__, "���ؽ���д���" . $response['error'] . "($response[error_code])");
            throw new Qihoo_Exception($response['error_code'], $response['error']);
        }
        return $response;
    }

    /**
     * ������־
     * @param type $location
     * @param type $msg
     * @return type
     */
    private function _debug($location, $msg)
    {
        if (!$this->_logger) {
            return;
        }

        $this->_logger->log($location, $msg);
    }

    /**
     * ʹ��refresh_token��ˢ��access_token
     *
     * @param string $refreshToken     A string of refresh token.
     * @param string $scope             Scope limit.
     *
     * @return a new access token and refresh token.
     */
    function getAccessTokenByRefreshToken($refreshToken)
    {
        $data = array(
            'grant_type' => "refresh_token",
            'refresh_token' => $refreshToken,
            'client_id' => $this->_clientId,
            'client_secret' => $this->_clientSecret,
            'scope' => $this->_scope,
        );
        return $this->_request(self::ACESSTOKEN_URL, $data);
    }

    /**
     * ��ȡ�û���Ϣ
     * @param String $tokenStr
     * @return array
     */
    public function userMe($tokenStr)
    {
        $url = self::HOST;
        $url .= '/user/me.json';
        $data = array(
            'access_token' => $tokenStr,
        );

        return $this->_request($url, $data);
    }

    /**
     * ��ȡtoken��Ӧ����Ϣ�������������token�Ƿ���Ч
     * @param String $tokenStr
     * @return array
     */
    public function getTokenInfo($tokenStr)
    {
        $data = array(
            'access_token' => $tokenStr,
        );
        return $this->_request(self::HOST . '/oauth2/get_token_info.json', $data);
    }

}

