<?php

class BasketWidget extends ExtendedWidget
{
    public $model;
    public $attribute;

    public function run()
    {
        $count = 0;

        $this->render('basket', array(
            'count' => $count
        ));
    }
}
