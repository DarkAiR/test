<?php

class Claim
{
    public $orderId;                    // string min = 8 да Идентификатор завки 
    public $personId;                   // * string min = 8 да Идентификатор персоны 
    public $createDate;                 // date yyyy-MM-dd HH:mm нет дата создания заявки 
    public $statusDate;                 // date yyyy-MM-dd HH:mm нет дата последнего статуса 
    public $statusCode;                 // String да код последнего статуса заявки
    public $statusComment;              // String да коментарий последнего статуса
    public $srguServiceId;              // String нет код процедуры в СРГУ 
    public $srguServiceName;            // String нет название процедуры в СРГУ 
    public $srguDepartmentId;           // String нет код ведомства в СРГУ 
    public $srguDepartmentName;         // String нет Название ведомства СРГУ 
    public $srguServicePassportId;      // string нет код услуги СРГУ
    public $srguServicePassportName;    // string нет Название услуги СРГУ 
    public $okato;                      // string нет ОКАТО региона 
    public $mkguId;                     // string нет Идентификатор вендера ИАС МКГУ
    public $soap;                       // string нет Тело запроса ВИС 
    public $eticketId;                  // string нет Идентификатор обращения в электронную очередь
    public $eticketDate;                // string ddmmyyyy нет Дата обращения в электронную очередь
    public $historyItems;               // [ClaimStatus...] нет список статусов заявки 
};
