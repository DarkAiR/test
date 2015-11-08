<?php

class m150603_113011_main_menu extends ExtendedDbMigration
{
    public function up()
    {
        $menu = array_merge(
            array('id' => Menu::MAIN_MENU),
            $this->createLangData('name', 'Главное меню', 'menu')                   // Yii::t('menu', 'Главное меню')
        );

        $items = array(
            array_merge(
                array('link' => 'brooches'),
                $this->createLangData('name', 'Брошки', 'menu')                     // Yii::t('menu', 'Брошки')
            ),
            array_merge(
                array('link' => 'posts'),
                $this->createLangData('name', 'Открытки и магниты', 'menu')         // Yii::t('menu', 'Открытки и магниты')
            ),
            array_merge(
                array('link' => 'news'),
                $this->createLangData('name', 'Новости и блог', 'menu')             // Yii::t('menu', 'Новости и блог')
            ),
            array_merge(
                array('link' => 'info'),
                $this->createLangData('name', 'Информация о доставке', 'menu')      // Yii::t('menu', 'Информация о доставке')
            ),
            array_merge(
                array('link' => 'contacts'),
                $this->createLangData('name', 'Контакты', 'menu')                   // Yii::t('menu', 'Контакты')
            )
        );

        $this->createMenu($menu, $items);
    }

    public function down()
    {
        $this->deleteMenu(Menu::MAIN_MENU);
    }
}
