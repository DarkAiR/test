<?php

/**
 * Новость
 */
class News extends CActiveRecord
{
    const IMAGE_SMALL_W = 242;
    const IMAGE_SMALL_H = 136;

    public $_image = null; //UploadedFile[]
    public $_removeImageFlag = false; // bool

    public $createTimeDate = '';
    public $createTimeTime = '';

    public $_docs = array();
    public $_removeDocs = array();


    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function fillDefault()
    {
        $this->createTimeDate = date('d.m.Y');
        $this->createTimeTime = date('H:i');
    }

    public function behaviors()
    {
        return array(
            // 'manyToMany' => array(
            //     'class' => 'lib.ar-relation-behavior.EActiveRecordRelationBehavior',
            // ),
            'imageBehavior' => array(
                'class' => 'application.behaviors.ImageBehavior',
                'storagePath' => 'news',
//                'imageWidth' => self::IMAGE_SMALL_W,
//                'imageHeight' => self::IMAGE_SMALL_H,
                'imageField' => 'image',
                'imageLabel' => 'Маленькая картинка',
            ),
            'docBehavior' => array(
                'class' => 'application.behaviors.DocumentsBehavior',
                'docField' => 'docs',
                'docExt' => 'pdf, doc, docx, xls',
                'storagePath' => 'docs/news',
            ),
            'timeBehavior' => array(
                'class' => 'application.behaviors.TimeBehavior',
                'createTimeField' => 'createTime',
            ),
            'languageBehavior' => array(
                'class'                 => 'application.behaviors.MultilingualBehavior',
                'langClassName'         => 'NewsLang',
                'langTableName'         => 'News_lang',
                'langForeignKey'        => 'newsId',
                'localizedAttributes'   => array('title', 'shortDesc', 'desc'),
                'languages'             => Yii::app()->params['languages'],
                'defaultLanguage'       => Yii::app()->sourceLanguage,
                'dynamicLangClass'      => true,
            ),
        );
    }

    public function relations()
    {
        return array(
        );
    }

    public function attributeLabels()
    {
        return array_merge(
            $this->imageBehavior->imageLabels(),
            $this->docBehavior->docLabels(),
            $this->timeBehavior->timeLabels(),
            $this->languageBehavior->languageLabels(array(
                'title'     => 'Заголовок',
                'shortDesc' => 'Короткое описание',
                'desc'      => 'Текст',
            )),
            array(
                'onMain' => 'Показывать на главной',
                'visible' => 'Показывать',
                'newsLink' => 'Ссылка на новость',
                'createTimeTime' => 'Время создания',
                'createTimeDate' => 'Дата создания'
            )
        );
    }


    public function rules()
    {
        return array_merge(
            $this->imageBehavior->imageRules(),
            $this->docBehavior->docRules(),
            $this->timeBehavior->timeRules(),
            array(
                array('title, desc, shortDesc', 'safe'),
                array('onMain, visible', 'boolean'),
                array('createTimeTime, createTimeDate', 'safe'),
                array('title, shortDesc, createTimeTime, createTimeDate', 'required'),
                array('_image', 'requiredImageValidator'),

                array('title, desc, shortDesc', 'safe', 'on'=>'search'),
            )
        );
    }

    public function requiredImageValidator($attribute,$params)
    {
        $value = $this->$attribute;
        $isEmpty = ($value===null || $value===array() || $value==='');

        $value = $this->image;
        $isEmptyImage = ($value===null || $value===array() || $value==='');

        if ($isEmpty && $isEmptyImage) {
            $message = Yii::t('yii','{attribute} cannot be blank.');
            $params['{attribute}'] = $this->getAttributeLabel($attribute);
            $this->addError($attribute, strtr($message,$params));
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

    public function defaultScope()
    {
        return $this->languageBehavior->localizedCriteria();
    }

    public function scopes()
    {
        $alias = $this->getTableAlias();
        return array(
            'onSite' => array(
                'condition' => $alias.'.visible = 1',
            ),
            'onMain' => array(
                'condition' => $alias.'.onMain = 1',
            ),
            'orderDefault' => array(
                'order' => $alias.'.createTime DESC',
            ),
        );
    }


    public function byLimit($limit)
    {
        $this->getDbCriteria()->mergeWith(array(
            'limit' => $limit,
        ));
        return $this;
    }


    public function byYear($year)
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => "DATE_FORMAT(FROM_UNIXTIME(createTime),'%Y') = :year",
            'params' => array(
                'year' => $year,
            ),
        ));
        return $this;
    }


    public function search()
    {
        $criteria = new CDbCriteria;
        //$criteria->compare('name', $this->name, true);
        return new CActiveDataProvider($this, array(
            'criteria' => $this->languageBehavior->modifySearchCriteria($criteria),
            'pagination' => array(
                'pageSize' => 20,
            ),
            'sort' => array(
                'defaultOrder' => array(
                    'onMain' => CSort::SORT_DESC,
                    'id' => CSort::SORT_DESC,
                )
            )
        ));
    }

    public function getNewsLink()
    {
        return self::getNewsLinkById($this->id);
    }

    public static function getNewsLinkById($id)
    {
        return CHtml::normalizeUrl( array(0=>'/news/news/show', 'id'=>$id) );
    }

    public function getImageUrl()
    {
        return $this->imageBehavior->getImageUrl();
    }

    public function getDocUrl($filename)
    {
        return $this->docBehavior->getDocUrl($filename);
    }

    protected function afterDelete()
    {
        $this->imageBehavior->imageAfterDelete();
        $this->docBehavior->docAfterDelete();
        return parent::afterDelete();
    }

    protected function beforeFind()
    {
        // Поддержка многих языков после загрузки модели
        $this->languageBehavior->multilang();
        return parent::beforeFind();
    }

    protected function afterFind()
    {
        $this->imageBehavior->imageAfterFind();
        $this->docBehavior->docAfterFind();
        $this->timeBehavior->timeAfterFind();

        $this->createTimeDate = date('d.m.Y', $this->createTime);
        $this->createTimeTime = date('H:i', $this->createTime);

        return parent::afterFind();
    }

    protected function beforeSave()
    {
        if (!empty($this->createTimeDate) && !empty($this->createTimeTime))
            $this->createTime = strtotime($this->createTimeDate.' '.$this->createTimeTime);

        $this->docBehavior->docBeforeSave();
        $this->timeBehavior->timeCreate();
        return parent::beforeSave();
    }
}
