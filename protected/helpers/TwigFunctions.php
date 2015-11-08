<?php

class TwigFunctions
{
    /**
     * @param string $class
     * @param array $properties
     * @return string
     */
    public static function widget($class, $properties = array())
    {
        $className = Yii::import($class, true);
        foreach ($properties as $propertyName => $value)
        {
            if (!property_exists($className, $propertyName))
                unset($properties[$propertyName]);
        }

        $c = Yii::app()->getController();
        return $c->widget($class, $properties, true);
    }

    /**
     * @param string $class
     * @param string $property
     * @return mixed
     */
    public static function constGet($class, $property)
    {
        $c = new ReflectionClass($class);
        return $c->getConstant($property);
    }

    /**
     * @param string $class
     * @param string $method
     * @param array $params
     * @return mixed
     */
    public static function staticCall($class, $method, $params = array())
    {
        return call_user_func_array($class . '::' . $method, $params);
    }

    public static function call($function, $params = array())
    {
        return call_user_func_array($function, $params);
    }

    /**
     * Добавить CSS
     */
    public static function importResource($type, $filename, $alias=false)
    {
        switch ($type)
        {
            case 'css':
                if ($alias === false)
                    $alias = 'application.views.css';
                $assetsPath = Yii::app()->assetManager->publish(Yii::getPathOfAlias($alias)."/".$filename);
                Yii::app()->getClientScript()->registerCssFile($assetsPath, '');
                break;

            case 'js':
                if ($alias === false)
                    $alias = 'application.views.js';
                $assetsPath = Yii::app()->assetManager->publish(Yii::getPathOfAlias($alias)."/".$filename);
                Yii::app()->getClientScript()->registerScriptFile($assetsPath);
                break;
        }
    }

    /**
     * Создать абсолютую ссылку
     */
    public static function absLink($link)
    {
        return Yii::app()->request->hostInfo.'/'.ltrim($link,'/');
    }
    /**
     * Множественная форма
     * @param  integer $num  Число для сравнения
     * @param  array $vars Варианты
     * @return string       результат
     */
    public static function plural($num, $vars)
    {
        return $num % 10 == 1 && $num % 100 != 11
            ? $vars[0]
            : $num % 10 >= 2 && $num % 10 <= 4 && ($num % 100 < 10 || $num % 100 >= 20)
                ? $vars[1]
                : $vars[2];
    }

    public static function filterUnset($array, $elementName)
    {
        unset($array[$elementName]);

        return $array;
    }

    public static function filterDate($ts, $format)
    {
        if (!is_string($format) || !is_int($ts))
            return '';

        return date($format, $ts);
    }

    public static function filterTranslit($st)
    {
        // Сначала заменяем "односимвольные" фонемы.
        $st = strtr($st,"абвгдеёзийклмнопрстуфхъыэ ", "abvgdeeziyklmnoprstufh'ie_");
        $st = strtr($st,"АБВГДЕЁЗИЙКЛМНОПРСТУФХЪЫЭ", "ABVGDEEZIYKLMNOPRSTUFH'IE");
        
        // Затем - "многосимвольные".
        $st = strtr($st, array(
            "ж"=>"zh", "ц"=>"ts", "ч"=>"ch", "ш"=>"sh", 
            "щ"=>"shch","ь"=>"", "ю"=>"yu", "я"=>"ya",
            "Ж"=>"ZH", "Ц"=>"TS", "Ч"=>"CH", "Ш"=>"SH", 
            "Щ"=>"SHCH","Ь"=>"", "Ю"=>"YU", "Я"=>"YA",
            "ї"=>"i", "Ї"=>"Yi", "є"=>"ie", "Є"=>"Ye"
        ));
        return $st;
    }

    public static function filterExternalLink($url)
    {
        if (strpos($url, 'http')===0)
            return $url;
        return 'http://'.$url;
    }

    public static function filterFixSkype($str)
    {
        return LocalConfigHelper::fixSkype($str);
    }
}
