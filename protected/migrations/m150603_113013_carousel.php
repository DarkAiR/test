<?php


class m150603_113013_carousel extends CDbMigration
{
    public function safeUp()
    {
        $this->execute("
            CREATE TABLE IF NOT EXISTS `Carousel` (
                `id`            int(11) NOT NULL AUTO_INCREMENT,
                `image`         varchar(100) NOT NULL           COMMENT 'Картинка',
                `link`          TEXT NOT NULL                   COMMENT 'Ссылка',
                `orderNum`      int(10) NOT NULL DEFAULT '0'    COMMENT 'Порядок',
                `visible`       tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Видимость',
                PRIMARY KEY (`id`),
                KEY `orderNum` (`orderNum`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
    }

    public function safeDown()
    {
        $this->dropTable('Carousel');
    }
}