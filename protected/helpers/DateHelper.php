<?php

class DateHelper
{
    /**
     * Дата для новостей
     */
    public static function formatNewsDate($time)
    {
        return Yii::app()->dateFormatter->format('d MMMM', $time);
    }
}