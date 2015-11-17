<?php

// curl -i 'http://91.241.12.198:8181/api/claims/schema' -H 'Accept: application/schema+json'

final class Claim
{
    public $id                      = null;     // String       Идентификатор заявки
    public $state                   = null;     // ClaimState   (INIT, PROGRESSES, DENIED, COMPLETED) Состояние заявки
    public $soapId                  = null;     // String       Идентификатор soap запроса (для интеграционных)
    public $persons                 = null;     // Set<String>  Список заявителей
    public $oktmo                   = null;     // String       ОКТМО ведомства
    public $okato                   = null;     // String       ОКАТО ведомства
    public $senderCode              = null;     // String       Код отправителя
    public $senderName              = null;     // String       Наименование отправителя
    public $providerCode            = null;     // String       Код получателя
    public $providerName            = null;     // String       Наименование получателя
    public $placeOfIssue            = null;     // ClaimPlaceOfIssue (DEPARTMENT, MFC, COURIER) Место выдачи результата
    public $claimCreate             = null;     // Date         Дата создания заявки
    public $service                 = null;     // Service      процедура
    public $mkguId                  = null;     // String       Идентификатор ИАС МКГУ
    public $eticketId               = null;     // String       код талона Авилекс (ЭО)
    public $eticketDate             = null;     // String       Дата вызова талона (ЭО)
    public $currStatus              = null;     // ClaimStatus  Последний статус заявки
    public $trustedPersons          = null;     // LinkedHashMap<String, TrustedPerson> Список доверенных, доп.получателей и т.п.
    public $fields                  = null;     // AdditionalField Атрибуты заявки
    public $payments                = null;     // Set<Payment> Информация о платежах
    public $operatorId              = null;     // String       Идентификатор пользователя, работающего с заявкой последним
    public $deadline                = null;     // Integer      Срок оказания в днях по регламенты
    public $deadlineDate            = null;     // Date         Дата завершения по регламенты
    public $daysToDeadline          = null;     // Integer      Дней до истечения регламентного срока
    public $resolutionNumber        = null;     // String       Номер решения
    public $resolutionDate          = null;     // Date         Дата решения
    public $prevResolutionNumber    = null;     // String       Номер предыдущего решения
    public $prevResolutionDate      = null;     // Date         Дата предыдущего решения
    public $deadlineInWorkDays      = null;     // Boolean      Считать регламентный срок в рабочих днях
    public $resultStatus            = null;     // String       Итоговый статус
    public $plannedDate             = null;     // Date         Планируемая дата завершения обработки (по регламенту)
    public $docSendDate             = null;     // Date         Дата отправки МВ запроса
    public $prepareDate             = null;     // Date         Дата подготовки заявки
    public $customClaimNumber       = null;     // String       Дополнительный уникальный номер 
    public $comment                 = null;     // String       комментарий
    public $read                    = null;     // Boolean      Служебное поле
    public $consultation            = null;     // Boolean      Заявка является консультацией
    public $deptId                  = null;     // String       Служебное поле 

    public $urmName                 = null;     // String
    public $urmNumber               = null;     // String
    public $provLevel               = null;     // String
    public $serviceOrg              = null;     // String
    public $person                  = null;     // Person
    public $createDate              = null;     // Date
    public $lastModified            = null;     // Date
    // public $lastModifiedBy          = null;     // String
    public $createState             = null;     // createState (INIT, DENIED, COMPLETED, INVALIDATE)
    // public $createBy                = null;     // String

    private $personsData = array();     // Данные о персонах

    public function __construct($data)
    {
        foreach ($data as $k => $v) {
            switch (strtolower($k)) {
                case 'currstatus':
                    $this->$k = new ClaimStatus($v);
                    break;

                case 'service':
                    $this->$k = new Service($v);
                    break;

                default:
                    $this->$k = $v;
                    break;
            }
        }
        if (is_array($this->persons)) {
            $this->personsData = RlddConnect::findPersonsByIds($this->persons);
        }
    }

    public function getPersonsData()
    {
        return $this->personsData;
    }

    public function getStatus()
    {
        
    }
}
