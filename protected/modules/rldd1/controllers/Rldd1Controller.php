<?php

/**
 * Класс для общения с РЛДД v1.0
 */

Yii::import('application.modules.rldd.helpers.*');
Yii::import('application.modules.rldd.structures.*');

class Rldd1Controller extends Controller
{
    public function actionIndex()
    {
/*        $claim = new ClaimRequest();
        $claim->senderName = 'rldd_test';
        $claim->senderCode = 'rldd_test_code';
        $claim->statusCode = '0';
        $claim->srguDepartmentId = '00000000';
        $claim->srguServiceId = '00000000';
        $claim->personId = '123123123';
        $res = RlddConnect::createClaim($claim);
        echo '<pre>';
        var_dump($res);
        die;
*/
        $this->render('/index', array());
    }

    /**
     * Зарегистрировать персону
     * @return JSON
     *      {FIELD_NAME:error,...}      ошибки по полям
     *      {error, errmsg}             внутренняя ошибка (error-текст для отображения, errmsg-внутренний текст)
     *      {result:ID, msg}            id персоны и текст сообщения
     */
    public function actionRegister()
    {
        if (!Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404);

        $login      = Yii::app()->request->getPost('login', '');
        $email      = Yii::app()->request->getPost('email', '');

        // Обязательные параметры
        $errors = array();
        if (empty($login))
            $errors['login'] = Yii::t('rlddError', 'Не задан логин');
        if (!empty($errors)) {
            header('HTTP/1.1 400 Params error');
            echo json_encode($errors);
            Yii::app()->end();
        }

        // Ищем пользователя по обязательному логину
        $params['login'] = $login;
        $res = RlddConnect::getPerson($params);

        // Если не пришла ошибка, то такой пользователь существует, считаем это ошибкой
        if (!isset($res['errmsg'])) {
            header('HTTP/1.1 400 User error');
            echo json_encode( array(
                'error' => Yii::t('rlddError', 'Такой пользователь уже существует'),
                'errmsg' => Yii::t('rlddError', 'Такой пользователь уже существует'),
            ));
            Yii::app()->end();
        }

        // Несколько пользователей под одним логином
        if (strpos(strtolower($res['errmsg']), RlddConnect::ERROR_PERSON_DUPLICATE) !== false) {
            header('HTTP/1.1 400 User error');
            echo json_encode( array(
                'error' => Yii::t('rlddError', 'Такой пользователь уже существует'),
                'errmsg' => Yii::t('rlddError', 'Такой пользователь уже существует'),
            ));
            Yii::app()->end();
        }

        // Все остальные ошибки считаем посто ошибками
        if (strpos(strtolower($res['errmsg']), RlddConnect::ERROR_PERSON_NOT_FOUND) === false) {
            header('HTTP/1.1 400 User error');
            echo json_encode( array(
                'error' => Yii::t('rlddError', 'Ошибка при регистрации пользователя'),
                'errmsg' => $res['errmsg'],
            ));
            Yii::app()->end();
        }

        // Записываем необязательные поля
        if (!empty($email))
            $params['email'] = $email;

        // Создаем пользователя
        $res = RlddConnect::createPerson($params);
        if (isset($res['errmsg'])) {
            // Если не пришла ошибка, то такой пользователь существует, считаем это ошибкой
            header('HTTP/1.1 400 User error');
            echo json_encode(array(
                'error' => Yii::t('rlddError', 'Не получилось создать пользователя'),
                'errmsg' => $res['errmsg'],
            ));
            Yii::app()->end();
        }
        echo json_encode(array(
            'personId' => $res['result'],
            'msg' => Yii::t('rldd', 'Пользователь с именем {login} успешно создан. Теперь вы можете использовать сервис.', array('{login}'=>$login))
        ));
        Yii::app()->end();
    }

    /**
     * Логин
     * @return JSON
     *      {FIELD_NAME:error,...}      ошибки по полям
     *      {error, errmsg}             внутренняя ошибка (error-текст для отображения, errmsg-внутренний текст)
     *      {result:ID, msg}            id персоны и текст сообщения
     */
    public function actionLogin()
    {
        if (!Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404);

        $login      = Yii::app()->request->getPost('login', '');
        $password   = Yii::app()->request->getPost('password', '');
        $email      = Yii::app()->request->getPost('email', '');

        // Обязательные параметры
        $errors = array();
        if (empty($login))
            $errors['login'] = Yii::t('rlddError', 'Не задан логин');
        if (!empty($errors)) {
            header('HTTP/1.1 400 Params error');
            echo json_encode($errors);
            Yii::app()->end();
        }

        // Создаем пользователя
        $params = array('login' => $login);
        if (!empty($password))
            $params['password'] = $password;
        if (!empty($email))
            $params['email'] = $email;
        $res = RlddConnect::getPerson($params);
        if (isset($res['errmsg'])) {
            // Пользователь не найден
            if (strpos(strtolower($res['errmsg']), RlddConnect::ERROR_PERSON_NOT_FOUND) !== false) {
                header('HTTP/1.1 400 User error');
                echo json_encode( array(
                    'error' => Yii::t('rlddError', 'Пользователь не найден'),
                    'errmsg' => $res['errmsg'],
                ));
                Yii::app()->end();
            }

            // Несколько пользователей под одним логином
            if (strpos(strtolower($res['errmsg']), RlddConnect::ERROR_PERSON_DUPLICATE) !== false) {
                header('HTTP/1.1 400 User error');
                echo json_encode( array(
                    'error' => Yii::t('rlddError', 'Не хватает данных для входа. Попробуйте заполнить остальные поля.'),
                    'errmsg' => $res['errmsg'],
                ));
                Yii::app()->end();
            }
        }

        echo json_encode(array(
            'personId' => $res['result'],
            'msg' => ''
        ));
        Yii::app()->end();
    }

    /**
     * Список сервисов
     */
    public function actionServices()
    {
        $personId = Yii::app()->request->getPost('personId');
        if (!$personId)
            throw new CHttpException(404);

        $this->render('/services', array(
            'personId' => $personId
        ));
    }
}

/**
        //$res = RlddConnect::login('da', 'da');


        $res = $personId;

        $claimData = new ClaimRequest();
        //$claimData->orderId = '';
        $claimData->personId = $personId;

        $res = RlddConnect::createClaim($claimData);
        //$res = RlddConnect::getClaimByPersonId('55b89defe4b083afe1cda4e7');
        //$res = RlddConnect::getClaimByOrderId('523eea9d');

*/