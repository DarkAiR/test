<?php

class AdminCarouselController extends MAdminController
{
    public $modelName = 'Carousel';
    public $modelHumanTitle = array('изображение', 'изображения', 'изображений');

    public function behaviors()
    {
        return array(
            'imageBehavior' => array(
                'class' => 'application.behaviors.ImageControllerBehavior',
                'imageField' => 'image',
                'imageWidth' => Carousel::IMAGE_W,
                'imageHeight' => Carousel::IMAGE_H,
            ),
        );
    }

    public function getEditFormElements($model)
    {
        return array(
            '_image' => array(
                'class' => 'ext.ImageFileRowWidget',
                'uploadedFileFieldName' => '_image',
                'removeImageFieldName' => '_removeImageFlag',
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
            $this->getOrderColumn(),
            $this->getImageColumn('image', 'getImageUrl()'),
            'link',
            $this->getVisibleColumn(),
            $this->getButtonsColumn(),
        );

        return $attributes;
    }

    public function beforeSave($model)
    {
        $this->imageBehavior->imageBeforeSave($model, $model->imageBehavior->getStorePath());
        parent::beforeSave($model);
    }
}
