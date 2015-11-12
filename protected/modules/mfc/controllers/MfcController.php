<?php

Yii::import('application.modules.mfc.helpers.*');
Yii::import('application.modules.mfc.structures.*');

class MfcController extends Controller
{
    public function actionIndex()
    {
        $this->render('/index', array());
    }

    /**
     * Зарегистрировать персону
     * @return JSON
     *      {FIELD_NAME:error,...}       ошибки по полям
     *      {error,errmsg}               внутренняя ошибка (error-текст для отображения, errmsg-внутренний текст)
     */
    public function actionRegister()
    {
        if (!Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404);

        $login      = Yii::app()->request->getPost('login', '');
        $password   = Yii::app()->request->getPost('password', '');
        $email      = Yii::app()->request->getPost('email', '');

        $errors = array();
        if (empty($login))
            $errors['login'] = Yii::t('mfc', 'Не задан логин');
        if (empty($password))
            $errors['password'] = Yii::t('mfc', 'Не задан пароль');

        if (!empty($errors)) {
            header('HTTP/1.1 500 Params error');
            echo json_encode($errors);
            Yii::app()->end();
        }

        $params = array();
        $res = RlddConnect::getPerson($params);
        if (isset($res['errmsg'])) {
            header('HTTP/1.1 500 User error');
            echo json_encode(array(
                'error' => Yii::t('mfc', 'Такой пользователь уже существует'),
                'errmsg' => $res['errmsg']
            ));
            Yii::app()->end();
        }
        echo json_encode($res);
    }

    public function actionCreatePerson()
    {
        if (!Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404);

        $login      = Yii::app()->request->getPost('login', '');
        $password   = Yii::app()->request->getPost('password', '');
        $email      = Yii::app()->request->getPost('email', '');

        $params['login'] = $login;
        $params['password'] = $password;
        $personData = RlddConnect::createPerson($params, 'false');
        if ($this->isJson($personData)) {
            $personData = json_decode($personData);
            echo json_encode(array('error'=>$personData->errmsg));
        } else {
            echo json_encode(array('personId'=>$personData));
        }
        Yii::app()->end();
    }

    private function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
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