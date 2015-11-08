<?php

class CompanyController extends Controller
{
    /**
     * Контакты
     */
    public function actionContacts()
    {
        $this->setBreadcrumbs(array('Контакты'));
        $this->render('/contacts', array());
    }
}