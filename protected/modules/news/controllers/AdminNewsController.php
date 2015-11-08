<?php

class AdminNewsController extends MAdminController
{
    public $modelName = 'News';
    public $modelHumanTitle = array('новость', 'новости', 'новостей');

    public function behaviors()
    {
        return array(
            'imageBehavior' => array(
                'class' => 'application.behaviors.ImageControllerBehavior',
                'imageField' => 'image',
                'imageWidth' => News::IMAGE_SMALL_W,
                //'imageHeight' => News::IMAGE_SMALL_H,
            ),
            'docBehavior' => array(
                'class' => 'application.behaviors.DocumentsControllerBehavior',
                'docField' => 'docs',
            ),
        );
    }

    public function getEditFormElements($model)
    {
        return array_merge(
            $this->getLangField(
                'title', array(
                    'type' => 'textField',
                )
            ),
            array(
                'createTimeDate' => array(
                    'type' => 'datepicker',
                    'htmlOptions' => array(
                        'options' => array(
                            'format' => 'dd.mm.yyyy'
                        ),
                    ),
                ),
                'createTimeTime' => array(
                    'type' => 'timepicker',
                    'htmlOptions' => array(
                        'options' => array(
                            'showMeridian' => false,
                            'defaultTime' => 'value',
                        ),
                    ),
                ),
            ),
            $this->getLangField(
                'shortDesc', array(
                    'type' => 'textarea',
                    'htmlOptions' => array(
                        'rows' => 10,
                    ),
                )
            ),
            $this->getLangField(
                'desc', array(
                    'type' => 'ckEditor',
                )
            ),
            array(
                '_image' => array(
                    'class' => 'ext.ImageFileRowWidget',
                    'uploadedFileFieldName' => '_image',
                    'removeImageFieldName' => '_removeImageFlag',
                ),
                'docs' => array(
                    'class' => 'application.components.admin.EditDocumentsWidget',
                    'innerField' => '_docs',
                    'attributeRemove' => '_removeDocs',
                    'maxRows' => 5,
                ),
                'onMain' => array(
                    'type' => 'checkBox',
                ),
                'visible' => array(
                    'type' => 'checkBox',
                ),
            )
        );
    }

    public function getTableColumns()
    {
        $attributes = array(
            'id',
            '_createTime',
            $this->getImageColumn('image', 'getImageUrl()'),
            'title',
            'newsLink',
            $this->getBooleanColumn('onMain'),
            $this->getVisibleColumn(),
            $this->getButtonsColumn(),
        );

        return $attributes;
    }

    public function beforeSave($model)
    {
        $this->imageBehavior->imageBeforeSave($model, $model->imageBehavior->getStorePath());
        $this->docBehavior->docBeforeSave($model, $model->docBehavior->getStorePath());
        parent::beforeSave($model);
    }

    public function beforeEdit($model)
    {
        if ($model->isNewRecord) {
            $model->fillDefault();
        }
    }
}
