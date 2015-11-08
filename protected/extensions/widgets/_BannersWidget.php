<?php

class BannersWidget extends ExtendedWidget
{
    public $model;
    public $attribute;

    public function run()
    {
        $banners = Banners::model()->onSite()->findAll();
        if (empty($banners))
            return;

        $this->render('banners', array(
            'banner' => $banners[array_rand($banners)]
        ));
    }
}
