<?php

class HeaderWidget extends ExtendedWidget
{
    public $model;
    public $attribute;

    public function run()
    {
        $this->render('header', array(
        ));
    }
}
