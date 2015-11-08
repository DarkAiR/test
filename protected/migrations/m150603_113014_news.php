<?php

class m150603_113014_news extends CDbMigration
{
    public function safeUp()
    {
        $this->execute("
            CREATE TABLE IF NOT EXISTS `News` (
                `id`            int(11) NOT NULL AUTO_INCREMENT,
                `createTime`    int(11) NOT NULL                COMMENT 'Время создания',
                `title`         varchar(100) NOT NULL           COMMENT 'Заголовок',
                `shortDesc`     text NOT NULL                   COMMENT 'Короткое описание',
                `desc`          text NOT NULL                   COMMENT 'Текст',
                `image`         varchar(100) NOT NULL           COMMENT 'Картинка',
                `docs`          TEXT NOT NULL                   COMMENT 'Документы в JSON',
                `onMain`        tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Показывать на главной',
                `visible`       tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Показывать',
                PRIMARY KEY (`id`),
                KEY `createTime` (`createTime`),
                KEY `onMain` (`onMain`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
        $this->execute("
            CREATE TABLE IF NOT EXISTS `News_lang` (
                `l_id`          int(11) NOT NULL AUTO_INCREMENT,
                `newsId`        int(11) NOT NULL,
                `lang_id`       varchar(6) NOT NULL,
                `l_title`       varchar(100) NOT NULL,
                `l_shortDesc`   text NOT NULL,
                `l_desc`        text NOT NULL,
                PRIMARY KEY (`l_id`),
                KEY `menuId` (`newsId`),
                KEY `lang_id` (`lang_id`),
                CONSTRAINT `fk_news_lang` FOREIGN KEY (`newsId`) REFERENCES `News` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
        ");
    }

    public function safeDown()
    {
        $this->dropTable('News_lang');
        $this->dropTable('News');
    }
}