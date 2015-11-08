<?php

class m150603_113012_footer_menu extends ExtendedDbMigration
{
    public function up()
    {
        $menu = array_merge(
            array('id' => Menu::FOOTER_MENU_1),
            $this->createLangData('name', 'Навигация по сайту', 'menu')             // Yii::t('menu', 'Навигация по сайту')
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
            )
        );
        $this->createMenu($menu, $items);

        $menu = array_merge(
            array('id' => Menu::FOOTER_MENU_2),
            $this->createLangData('name', 'Информация', 'menu')                     // Yii::t('menu', 'Информация')
        );
        $items = array(
            array_merge(
                array('link' => 'info'),
                $this->createLangData('name', 'Оплата и доставка', 'menu')          // Yii::t('menu', 'Оплата и доставка')
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
        $this->deleteMenu(Menu::FOOTER_MENU_1);
        $this->deleteMenu(Menu::FOOTER_MENU_2);
    }
}
