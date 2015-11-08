<?php

class AdminBannersController extends MAdminController
{
    public $modelName = 'Banners';
    public $modelHumanTitle = array('баннер', 'баннера', 'баннеров');

    public function behaviors()
    {
        return array(
            'imageBehavior' => array(
                'class' => 'application.behaviors.ImageControllerBehavior',
                'imageField' => 'image',
                'imageWidth' => Banners::IMAGE_W,
                'imageHeight' => Banners::IMAGE_H,
            ),
            'flashBehavior' => array(
                'class' => 'application.behaviors.FlashControllerBehavior',
                'flashField' => 'flash',
            ),
        );
    }

    public function getEditFormElements($model)
    {
        return array(
            'name' => array(
                'type' => 'textField',
            ),
            '_image' => array(
                'class' => 'ext.ImageFileRowWidget',
                'uploadedFileFieldName' => '_image',
                'removeImageFieldName' => '_removeImageFlag',
            ),
            '_flash' => array(
                'class' => 'ext.ImageFileRowWidget',
                'uploadedFileFieldName' => '_flash',
                'removeImageFieldName' => '_removeFlash',
            ),
            'link' => array(
                'type' => 'textField',
            ),
            'visible' => array(
                'type' => 'checkBox',
            ),
        );
    }

    public function getTableColumns()
    {
        $attributes = array(
            $this->getImageColumn('image', 'getImageUrl()'),
            'name',
            $this->getVisibleColumn(),
            $this->getButtonsColumn(),
        );

        return $attributes;
    }

    public function beforeSave($model)
    {
        $this->imageBehavior->imageBeforeSave($model, $model->imageBehavior->getStorePath());
        $this->flashBehavior->flashBeforeSave($model, $model->flashBehavior->getStorePath());
        parent::beforeSave($model);
    }
}
