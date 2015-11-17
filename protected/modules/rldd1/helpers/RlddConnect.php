<?php

Yii::import('application.modules.rldd.structures.*');

// Нужно указать один из параметров
/*class CreatePersonData
{
    public $login;
    public $password;
    public $personEMail;
    public $userId;
    public $personSNILS;
    public $personINN;
    public $principalDocument;
};
*/

class RlddConnect
{
    const URL = '91.241.12.198';
    const PORT = 8081;

    // Тексты ошибок, возвращаемых RLDD
    const ERROR_PERSON_NOT_FOUND = 'not found person by esia params';
    const ERROR_PERSON_DUPLICATE = 'duplicated, found some persons';

    /**
     * Login request
     * NOTE: not used
     */
    public static function login($username, $password)
    {
        $url = self::createUrl('/login?username={username}?password={password}', array(
            'username' => $username,
            'password' => $password
        ));
        $res = self::sendRequest('GET', $url);
        return $res;
    }

    /**
     * Получить персону
     */
    public static function getPerson($params)
    {
        $url = self::createUrl('/persons/esia?update=false', array(
            'update' => 'false'
        ));
        $res = self::sendRequest('PUT', $url, $params);
        return $res;
    }

    /**
     * Создать персону
     * @params $params
        Нужно указать один из параметров
        - login string Логин 
        - password string Пароль 
        - personEMail string Адрес электронной почты 
        - userId string Идентификатор пользователя в ЕСИА 
        - personSNILS string СНИЛС 
        - personINN string ИНН 
        - principalDocument Документ удостоверяющий личность 
                - number string Номер 
                - series string серия
     */
    public static function createPerson($params)
    {
        $url = self::createUrl('/persons/esia?update={update}', array(
            'update' => 'true'
        ));
        $res = self::sendRequest('PUT', $url, $params);
        return $res;
    }

    /**
     * Создать заявку
     */
    public static function createClaim(ClaimRequest $params)
    {
        $url = self::createUrl('/claims', array());

//        $params['senderCode'] = 'rldd_test_code';
//        $params['statusCode'] = '0';
//        $params['senderName'] = 'rldd_test';
//        $params['srguDepartmentId'] = '00000000';
//        $params['srguServiceId'] = '00000000';
        $params = self::cleanParams((array)$params);
        $res = self::sendRequest('POST', $url, $params);
        return $res;
    }

    /**
     * Получение заявок по ID персоны
     */
    public static function getClaimByPersonId($personId, $skip=0, $limit=10, $sort=1)
    {
        $url = self::createUrl('/claims/person/{personId}?skip={skip}&limit={limit}&sort={sort}', array(
            'personId' => $personId,
            'skip' => $skip,
            'limit' => $limit,
            'sort' => $sort
        ));
        $res = self::sendRequest('GET', $url);
        $res = json_decode($res);
        return $res;
    }

    /**
     * Получение заявок по ID персоны
     */
    public static function getClaimByOrderId($orderId, $withHistory='true')
    {
        $url = self::createUrl('/claims/order/{orderId}?withHistory={withHistory}', array(
            'orderId' => $orderId,
            'withHistory' => $withHistory,
        ));
        $res = self::sendRequest('GET', $url);
        $res = json_decode($res);
        return $res;
    }

    private static function createUrl($url, $params)
    {
        $search = array_map(
            function($n) {
                return '{'.$n.'}';
            },
            array_keys($params)
        );
        $replace = array_values($params);

        $url = str_replace($search, $replace, $url);
        return 'http://'.self::URL.':'.self::PORT.'/rldd'.$url;
    }

    /**
     * Отправка запроса, обработка ошибки и возврат результата в виде json
     * @return array
     *      ['errmsg'=>'...']               error
     *      ['result'=>'requestResult']     valid result, not json
     *      [...]                           valid result, json
     */
    private static function sendRequest($method, $url, $params)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'encoding : UTF-8'
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if (strtolower($method) != 'get')
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        $response = curl_exec($ch);

        $errno = curl_errno($ch);
        if ($errno)
            throw new Exception(curl_strerror($errno));

        if (self::isJson($response)) {
            $res = json_decode($response, true);
            if (isset($res['errmsg']))
                return array('errmsg'=>$res['errmsg']);       // Возвращаем ошибку
            return $res;
        }
        return array('result'=>$response);
    }

    private static function cleanParams($params)
    {
        return array_filter($params, function($v) {
            return $v !== null;
        });
    }

    private static function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
};
