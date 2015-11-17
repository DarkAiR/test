<?php

final class Service
{
    public $name                            = null; // String Наименование процедуры
    public $code                            = null; // String Идентификатор в АИС
    public $version                         = null; // String
    public $srguServiceId                   = null; // String Идентификатор процедуры в СРГУ
    public $srguServiceName                 = null; // String Наименование процедуры в СРГУ
    public $srguDepartmentId                = null; // String Идентификатор ведомства СРГУ
    public $srguDepartmentName              = null; // String Наименование ведомства СРГУ
    public $srguServicePassportId           = null; // String Идентификатор услуги СРГУ
    public $srguServicePassportName         = null; // String Наименование услуги СРГУ
    public $resolutionCopyAttachRequired    = null; // Boolean 
    public $serviceType                     = null; // ServiceType Тип услуги

    public function __construct($data)
    {
        foreach ($data as $k => $v) {
            $this->$k = $v;
        }
    }
}
