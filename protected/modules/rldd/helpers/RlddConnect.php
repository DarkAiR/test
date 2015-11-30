<?php

class RlddConnect
{
    const URL = '91.241.12.198';
    const PORT = 8081;

    /**
     * Получить персону по имени и фамилии
     */
    public static function findBySurnameAndFirstName($surname, $firstName)
    {
        $surname = urlencode(mb_strtolower($surname, 'UTF-8'));
        $firstName = urlencode(mb_strtolower($firstName, 'UTF-8'));
        $url = self::createUrl('/persons/search/3?surname={surname}&firstName={firstName}', array(
            'surname' => $surname,
            'firstName' => $firstName
        ));
        $res = self::sendRequest($url);
        if (!isset($res['_embedded']['persons'][0]))
            return false;
        return $res['_embedded']['persons'][0];
    }

    /**
     * Получить персону по ИНН
     */
    public static function findPersonByInn($inn)
    {
        $url = self::createUrl('/persons/search/0?inn={inn}', array(
            'inn' => $inn,
        ));
        $res = self::sendRequest($url);
        if (!isset($res['_embedded']['persons'][0]))
            return false;
        return $res['_embedded']['persons'][0];
    }

    /**
     * Получить персону по СНИЛС
     * @return Person
     */
    public static function findPersonBySnils($snils)
    {
        $url = self::createUrl('/persons/search/1?snils={snils}', array(
            'snils' => $snils,
        ));
        $res = self::sendRequest($url);
        if (!isset($res['_embedded']['persons'][0]))
            return false;
        return new Person($res['_embedded']['persons'][0]);
    }

    /**
     * Получить персоны по ID
     * @return Person
     */
    public static function findPersonsByIds($personIds)
    {
        if (!is_array($personIds))
            $personIds = array($personIds);

        $url = self::createUrl('/persons/search/6?ids={ids}', array(
            'ids' => implode(',',$personIds),
        ));
        $res = self::sendRequest($url);
        if (!isset($res['_embedded']['persons']))
            return array();

        $persons = array();
        foreach ($res['_embedded']['persons'] as $p) {
            $persons[] = new Person($p);
        }
        return $persons;
    }

    /**
     * Получить заявки для персон
     * @return array [
     *      'claims' => array(Claim)
     *      'page' => array
     * ]
     */
    public static function findClaimsByPersons($persons, $page, $size)
    {
        $url = self::createUrl('/claims/search/0?persons={persons}&page={page}&size={size}&sort={sort}', array(
            'persons' => implode(',', $persons),
            'page' => $page,
            'size' => $size,
            'sort' => 'claimCreate,desc'
        ));
        $res = self::sendRequest($url);
        $claims = array();
        if (isset($res['_embedded']['claims'])) {
            foreach ($res['_embedded']['claims'] as $data) {
                $claims[] = new Claim($data);
            }
        }
        $res['claims'] = $claims;
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
        return 'http://'.self::URL.':'.self::PORT.'/api'.$url;
    }

    /**
     * Отправка запроса, обработка ошибки и возврат результата в виде json
     * @return false | array
     */
    private static function sendRequest($url)
    {
        $res = @file_get_contents($url);
        $headers = self::parseHeaders($http_response_header);

        // Error (404, 500, etc)
        if ($headers['responseCode'] != 200)
            return false;

        if (!self::isJson($res))
            return false;

        $res = json_decode($res, true);
        return $res;
    }

    private static function parseHeaders($headers)
    {
        $head = array();
        foreach( $headers as $k=>$v )
        {
            $t = explode( ':', $v, 2 );
            if( isset( $t[1] ) )
                $head[ trim($t[0]) ] = trim( $t[1] );
            else
            {
                $head[] = $v;
                if( preg_match( "#HTTP/[0-9\.]+\s+([0-9]+)#",$v, $out ) )
                    $head['responseCode'] = intval($out[1]);
            }
        }
        return $head;
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
