<?php

class CarouselWidget extends ExtendedWidget
{
    public $model;
    public $attribute;

    public function run()
    {
        $images = Carousel::model()->onSite()->orderDefault()->findAll();

        $this->render('carousel', array(
            'images' => $images
        ));
    }
}
