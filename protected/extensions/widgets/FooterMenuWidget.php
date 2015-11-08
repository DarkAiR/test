<?php

Yii::import('ext.widgets.MenuWidget');

class FooterMenuWidget extends MenuWidget
{
    public $menuId = Menu::NONE;
    protected $template = 'footerMenu';


    public function getMenuTitle()
    {
        if ($this->menuId == Menu::NONE)
            return '';
        $menu = Menu::model()->findByPk($this->menuId);
        if (!$menu)
            return '';
        return $menu->name;
    }
}
