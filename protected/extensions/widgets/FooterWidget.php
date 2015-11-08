<?php

class FooterWidget extends ExtendedWidget
{
    public $model;
    public $attribute;

    public function run()
    {
        $this->render('footer', array(
        ));
    }
}
