<?php

/**
 * Класс для общения по REST с РЛДД v2.0
 */

Yii::import('application.modules.rldd.helpers.*');
Yii::import('application.modules.rldd.models.*');
Yii::import('application.models.*');

class RlddController extends Controller
{
    const TEST_PERSON_ID        = '562f9424e4b0102f8d883d83';
    const TEST_PERSON_ID_2      = '55704400e4b004cd770c739a';
    const TEST_INN              = '532165498722';
    const TEST_SNILS            = '32165498711';

    public function actionIndex()
    {
        if (Yii::app()->session['personId'])
            Yii::app()->request->redirect( CHtml::normalizeUrl(array(0=>'/rldd/rldd/claims')) );
        $this->render('/index', array());
    }

    /**
     * Войти по ФИО
     * @return JSON
     *      {personId:ID}       id персоны
     */
    public function actionLoginFio()
    {
        if (!Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404);

        //$surname    = Yii::app()->request->getPost('surname', '');
        //$firstName  = Yii::app()->request->getPost('firstName', '');
        //$middleName = Yii::app()->request->getPost('middleName', '');

        Yii::app()->session['personId'] = self::TEST_PERSON_ID;

        echo json_encode(array());
        Yii::app()->end();
    }

    /**
     * Войти по ИНН
     * @return JSON
     *      {FIELD_NAME:error,...}      ошибки по полям
     *      {error}                     ошибка
     *      {personId:ID}               id персоны и текст сообщения
     */
    public function actionLoginInn()
    {
        if (!Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404);

        $inn = Yii::app()->request->getPost('inn', '');

        // Обязательные параметры
        $errors = array();
        if (empty($inn))
            $errors['inn'] = Yii::t('rlddError', 'Не заданы обязательные параметры');
        if (!empty($errors)) {
            header('HTTP/1.1 400 Params error');
            echo json_encode($errors);
            Yii::app()->end();
        }

        $person = RlddConnect::findPersonByInn($inn);
        if (!$person) {
            header('HTTP/1.1 400 Params error');
            echo json_encode( array(
                'error' => Yii::t('rlddError', 'Пользователь не найден'),
            ));
            Yii::app()->end();            
        }

        Yii::app()->session['personId'] = $person->id;

        echo json_encode(array());
        Yii::app()->end();
    }


    /**
     * Войти по СНИЛС
     * @return JSON
     *      {FIELD_NAME:error,...}      ошибки по полям
     *      {error}                     ошибка
     *      {personId:ID}               id персоны и текст сообщения
     */
    public function actionLoginSnils()
    {
        if (!Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404);

        $snils = Yii::app()->request->getPost('snils', '');

        // Обязательные параметры
        $errors = array();
        if (empty($snils))
            $errors['snils'] = Yii::t('rlddError', 'Не заданы обязательные параметры');
        if (!empty($errors)) {
            header('HTTP/1.1 400 Params error');
            echo json_encode($errors);
            Yii::app()->end();
        }

        $person = RlddConnect::findPersonBySnils($snils);
        if (!$person) {
            header('HTTP/1.1 400 Params error');
            echo json_encode( array(
                'error' => Yii::t('rlddError', 'Пользователь не найден'),
            ));
            Yii::app()->end();            
        }

        Yii::app()->session['personId'] = $person->id;

        echo json_encode(array());
        Yii::app()->end();
    }

    /**
     * Получить заявки пользователя
     */
    public function actionClaims()
    {
        $page       = Yii::app()->request->getQuery('page', 1);
        $size       = 200;
        $personId   = Yii::app()->session['personId'];

        if (!$personId)
            Yii::app()->request->redirect(CHtml::normalizeUrl(array('/rldd/rldd/index')));

        $res = RlddConnect::findClaimsByPersons(array($personId), $page-1, $size);

        $criteria = new CDbCriteria();
        $criteria->offset = $page * $size;
        $criteria->limit  = $size;

        $pages = new CPagination($res['page']['totalElements']);
        $pages->pageSize = $size;
        $pages->applyLimit($criteria);
        $pages->currentPage = $page-1;

        $this->render('/claims', array(
            'claims' => $res['claims'],
            'pages' => $pages
        ));
    }
}
