<?php

/**
 * Описание события обновления статуса.
 */
final class ClaimStatus
{
    public $claimId     = null;     // String Идентификатор заявки, по которой обновляется статус
    public $statusDate  = null;     // Date Дата обновления статуса
    public $statusCode  = null;     // String Код статуса 
    public $senderCode  = null;     // String Мнемоника отправителя
    public $senderName  = null;     // String Наименование отправителя
    public $comment     = null;     // String комментарий 

    public function __construct($data)
    {
        foreach ($data as $k => $v) {
            $this->$k = $v;
        }
    }
}