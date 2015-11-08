<?php

class LocalConfigHelper
{
    /**
     * Replace shortcuts to local config params
     */
    public static function parseText($text)
    {
        $search = array(
            '%PHONE%',
            '%FAX%',
            '%EMAIL%',
            '%WORK_TIME_START%',
            '%WORK_TIME_END%',
        );
        $replace = array(
            Yii::app()->localConfig->getConfig('contact-info.phone', true),
            Yii::app()->localConfig->getConfig('contact-info.fax', true),
            Yii::app()->localConfig->getConfig('contact-info.email'),
            Yii::app()->localConfig->getConfig('contact-info.workTimeStart'),
            Yii::app()->localConfig->getConfig('contact-info.workTimeEnd'),
        );
        return str_replace($search, $replace, $text);
    }

    public static function fixSkype($v)
    {
        return implode( str_split($v, 5), '<span style="display:none;">_</span>');
    }
}
