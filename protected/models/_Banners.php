<?php

/**
 * Баннеры
 */
class Banners extends CActiveRecord
{
    const IMAGE_W = 699;
    const IMAGE_H = 44;

    public $_image = null; //UploadedFile[]
    public $_removeImageFlag = false; // bool

    public $_flash = null;
    public $_removeFlash = false;


    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function behaviors()
    {
        return array(
            'imageBehavior' => array(
                'class' => 'application.behaviors.ImageBehavior',
                'storagePath' => 'banners',
                'imageMaxWidth' => self::IMAGE_W,
                'imageHeight' => self::IMAGE_H,
                'imageField' => 'image',
                'imageExt' => 'jpeg, jpg, png',
            ),
            'flashBehavior' => array(
                'class' => 'application.behaviors.FlashBehavior',
                'storagePath' => 'banners',
                'flashField' => 'flash',
            ),
        );
    }

    public function attributeLabels()
    {
        return array_merge(
            $this->imageBehavior->imageLabels(),
            $this->flashBehavior->flashLabels(),
            array(
                'name' => 'Название',
                'link' => 'Ссылка',
                'visible' => 'Показывать',
            )
        );
    }

    public function rules()
    {
        return array_merge(
            $this->imageBehavior->imageRules(),
            $this->flashBehavior->flashRules(),
            array(
                array('name', 'required'),
                array('name, link', 'safe'),
                array('visible', 'boolean'),
                array('_image, _flash', 'requiredValidator'),
            )
        );
    }

    public function requiredValidator($attribute, $params)
    {
        $value = $this->_image;
        $isEmptyInnerImage = ($value===null || $value===array() || $value==='');

        $value = $this->image;
        $isEmptyImage = ($value===null || $value===array() || $value==='');

        $value = $this->_flash;
        $isEmptyInnerFlash = ($value===null || $value===array() || $value==='');

        $value = $this->flash;
        $isEmptyFlash = ($value===null || $value===array() || $value==='');

        if ($isEmptyInnerImage && $isEmptyImage && $isEmptyInnerFlash && $isEmptyFlash) {
            $message = Yii::t('yii','{attribute} cannot be blank.');
            $params['{attribute}'] = $this->getAttributeLabel('_image') . ' или ' . $this->getAttributeLabel('_flash');
            $this->addError('_image', strtr($message,$params));
            $this->addError('_flash', strtr($message,$params));
        }
    }

    /*
     Отмечаем значком "required"
     */
    public function isAttributeRequired($attribute)
    {
        if (in_array($attribute, array('_image, _flash')))
            return true;
        return parent::isAttributeRequired($attribute);
    }

    public function scopes()
    {
        $alias = $this->getTableAlias();
        return array(
            'onSite' => array(
                'condition' => $alias.'.visible = 1',
            ),
        );
    }

    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('name', $this->name, true);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => array(
                    'id' => CSort::SORT_DESC,
                )
            )
        ));
    }

    public function getImageUrl()
    {
        return $this->imageBehavior->getImageUrl();
    }

    public function getFlashUrl()
    {
        return $this->flashBehavior->getFlashUrl();
    }

    protected function afterDelete()
    {
        $this->imageBehavior->imageAfterDelete();
        $this->flashBehavior->flashAfterDelete();
        return parent::afterDelete();
    }

    protected function afterFind()
    {
        $this->imageBehavior->imageAfterFind();
        $this->flashBehavior->flashAfterFind();
        return parent::afterFind();
    }
}
