<?php

/**
 * Картинки для карусели
 */
class Carousel extends CActiveRecord
{
    const IMAGE_W = 980;
    const IMAGE_H = 486;

    public $_image = null; //UploadedFile[]
    public $_removeImageFlag = false; // bool

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function behaviors()
    {
        return array(
            'imageBehavior' => array(
                'class' => 'application.behaviors.ImageBehavior',
                'storagePath' => 'carousel',
                'imageWidth' => self::IMAGE_W,
                'imageHeight' => self::IMAGE_H,
                'imageField' => 'image',
                'imageExt' => 'jpeg, jpg',
            ),
            'orderBehavior' => array(
                'class' => 'application.behaviors.OrderBehavior',
            ),
        );
    }

    public function attributeLabels()
    {
        return array_merge(
            $this->imageBehavior->imageLabels(),
            $this->orderBehavior->orderLabels(),
            array(
                'link' => 'Ссылка',
                'visible' => 'Показывать',
            )
        );
    }

    public function rules()
    {
        return array_merge(
            $this->imageBehavior->imageRules(),
            $this->orderBehavior->orderRules(),
            array(
                array('link', 'safe'),
                array('visible', 'boolean'),
                array('_image', 'requiredValidator'),
            )
        );
    }

    public function requiredValidator($attribute, $params)
    {
        $value = $this->_image;
        $isEmptyInnerImage = ($value===null || $value===array() || $value==='');

        $value = $this->image;
        $isEmptyImage = ($value===null || $value===array() || $value==='');

        if ($isEmptyInnerImage && $isEmptyImage) {
            $message = Yii::t('yii','{attribute} cannot be blank.');
            $params['{attribute}'] = $this->getAttributeLabel('_image');
            $this->addError('_image', strtr($message,$params));
        }
    }

    /*
     Отмечаем значком "required"
     */
    public function isAttributeRequired($attribute)
    {
        if (in_array($attribute, array('_image')))
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
            'orderDefault' => array(
                'order' => $alias.'.orderNum ASC',
            ),
        );
    }

    public function search()
    {
        $criteria = new CDbCriteria;
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => array(
                    'orderNum' => CSort::SORT_ASC,
                )
            )
        ));
    }

    public function getImageUrl()
    {
        return $this->imageBehavior->getImageUrl();
    }

    protected function afterDelete()
    {
        $this->imageBehavior->imageAfterDelete();
        return parent::afterDelete();
    }

    protected function afterFind()
    {
        $this->imageBehavior->imageAfterFind();
        return parent::afterFind();
    }

    public function beforeSave()
    {
        $this->orderBehavior->orderBeforeSave();
        return parent::beforeSave();
    }
}
