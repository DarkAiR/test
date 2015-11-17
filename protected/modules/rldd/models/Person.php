<?php

/**
 * Метаданные карточки личного дела.
 */ 

// curl -i 'http://91.241.12.198:8181/api/persons/schema' -H 'Accept: application/schema+json'

final class Person
{
    public $id                      = null;         // string Идентификатор личного дела
    public $type                    = null;         // personType (PHYSICAL, JURIDICAL, IP) Тип личного дела
    public $inn                     = null;         // string Инн
    public $snils                   = null;         // string снилс
    public $contacts                = null;         // Set<Contact> Список контактов
    public $trustedPersons          = null;         // Set<TrustedPerson> Список доверенных лиц, доп.получателей и тд
    public $surname                 = null;         // string фамилия
    public $firstName               = null;         // string имя
    public $middleName              = null;         // string отчество
    public $age                     = null;         // integer возраст
    public $gender                  = null;         // gender (FEMALE, MALE) пол
    public $dateOfBirth             = null;         // string Дата рождения
    public $placeOfBirth            = null;         // string Место рождения
    public $citizenship             = null;         // string гражданство
    public $orgInn                  = null;         // string Инн организации
    public $orgType                 = null;         // string Тип организации
    public $orgName                 = null;         // string Наименование организации
    public $ogrn                    = null;         // string ОГРН
    public $kpp                     = null;         // string КПП
    public $branchKpp               = null;         // string КПП подразделения
    public $orgOid                  = null;         // string
    public $legalForm               = null;         // string Организационно-правовая форма
    public $shortName               = null;         // string Краткое наименование оргнизации
    public $firmName                = null;         // string
    public $okpo                    = null;         // string ОКПО
    public $okato                   = null;         // string ОКАТО
    public $okved                   = null;         // string Код ОКВЭД
    public $regDate                 = null;         // string Дата регистрации
    public $okfs                    = null;         // string
    public $bank                    = null;         // string Наименование банка
    public $bik                     = null;         // string БИК
    public $currAccount             = null;         // string Лицевой счет
    public $corrAccount             = null;         // string Кор. счет
    public $registrationAddressId   = null;         // string Идентификатор адреса регистрации
    public $locationAddressId       = null;         // string Идентификатор адреса места деятельности (жительства)
    public $birthAddressId          = null;         // string Идентификатор адреса места рождения
    public $ipWorkPlaceAddressId    = null;         // string Идентификатор рабочего адреса
    public $currIdentityDocId       = null;         // string Идентификатор документа, удостоверяющего личность
    public $createDate              = null;         // date
    public $lastModified            = null;         // date
    // public $lastModifiedBy          = null;         // string
    public $createState             = null;         // createState (INIT, DENIED, COMPLETED, INVALIDATE)
    // public $createBy                = null;         // string
    // public $lowerCaseSurname        = null;         // string
    // public $lowerCaseMiddleName     = null;         // string
    // public $lowerCaseFirstName      = null;         // string

    public function __construct($data)
    {
        foreach ($data as $k => $v) {
            $this->$k = $v;
        }
    }

    public function getName()
    {
        $name = '';

        if (!$this->firstName)
            return $name;
        $name = $this->firstName;

        if (!$this->surname)
            return $name;
        $name = $this->surname.' '.$name;

        if (!$this->middleName)
            return $name;
        $name = $name.' '.$this->middleName;

        return $name;
    }
};
