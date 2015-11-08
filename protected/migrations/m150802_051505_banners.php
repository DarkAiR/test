<?php


class m150802_051505_banners extends CDbMigration
{
    public function safeUp()
    {
        $this->execute("
            CREATE TABLE IF NOT EXISTS `Banners` (
                `id`            int(11) NOT NULL AUTO_INCREMENT,
                `image`         varchar(100) NOT NULL           COMMENT 'Картинка',
                `flash`         varchar(100) NOT NULL           COMMENT 'Флеш',
                `name`          varchar(100) NOT NULL           COMMENT 'Название',
                `link`          TEXT NOT NULL                   COMMENT 'Ссылка',
                `visible`       tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Видимость',
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
    }

    public function safeDown()
    {
        $this->dropTable('Banners');
    }
}