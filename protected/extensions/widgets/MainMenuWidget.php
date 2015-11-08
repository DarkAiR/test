<?php

Yii::import('ext.widgets.MenuWidget');

class MainMenuWidget extends MenuWidget
{
    protected $menuId = Menu::MAIN_MENU;
    protected $template = 'mainMenu';
}
