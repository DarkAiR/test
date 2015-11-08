<?php

class m150604_070306_contact_info extends CDbMigration
{
    public function safeUp()
    {
        $this->execute('
            INSERT INTO localconfig
                (`name`, `value`, `module`, `description`, `example`, `type`)
            VALUES
                ("phone", "+7 (3452) 60-68-64", "contact-info", "Телефон", "+7 (3452) 60-68-64", "string"),
                ("fax", "+7 (3452) 77-44-04", "contact-info", "Факс", "+7 (3452) 77-44-04", "string"),
                ("email", "uk@p-b-p.ru", "contact-info", "Почта", "uk@p-b-p.ru", "string"),
                ("workTimeStart", "09:00", "contact-info", "Время начала работы", "09:00", "string"),
                ("workTimeEnd", "20:00", "contact-info", "Время окончания работы", "20:00", "string")
        ');
    }

    public function safeDown()
    {
        $this->execute("
            DELETE FROM localconfig WHERE name IN ('phone', 'fax', 'email', 'workTimeStart', 'workTimeEnd')
        ");
    }
}

