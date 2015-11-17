<?php

final class StatusCode
{
    public static function getStatusText($statusCode)
    {
        switch ($statusCode) {
            default:
            case 8: return Yii::t('claim_status', 'Неизвестный статус');              // (не отображать) Обработка заявления

            case 0: return Yii::t('claim_status', 'Черновик заявления');              // Формирование заявления : Состояние, когда пользователь ещё не отправил заявление
            case 1: return Yii::t('claim_status', 'Принято от заявителя');            // Отправка заявления : ИС ФОИВ (сервер) принял заявление
            case 2: return Yii::t('claim_status', 'Отправлено в ведомство');          // Отправка заявления : Заявление послано, но ФОИВ ещё не вернул ответ
            case 5: return Yii::t('claim_status', 'Ошибка отправки в ведомство');     // Отправка заявления : Произошла техническая ошибка при передаче в ИС ФОИВ, например тайм-аут.

            case 1: return Yii::t('claim_status', 'Ошибка обработки результата');    // Обработка заявления : Возвращается в случае внутренней ошибки ИС ведомства с комментарием, поясняющим причину (или характер) ошибки.
            case 3: return Yii::t('claim_status', 'Услуга исполнена');                // Обработка заявления : Результат готов. Приглашение на получение результата.
            case 4: return Yii::t('claim_status', 'Отказ в оказании услуги');         // Обработка заявления : ИС может вернуть заявление по многим причинам некорректного заполнения полей формы.
            case 6: return Yii::t('claim_status', 'Принято ведомством');              // Обработка заявления : ОИВ принял заявление к рассмотрению
            case 7: return Yii::t('claim_status', 'Промежуточные результаты от ведомства');           // Обработка заявления : ОИВ получил заявление, но ещё не прошёл контроль целостности
            case 12: return Yii::t('claim_status', 'Входящее Сообщение');             // (не отображать) Обработка заявления
            case 13: return Yii::t('claim_status', 'Услуга оказана, просим подойти за результатом');  // Обработка заявления
            case 14: return Yii::t('claim_status', 'Услуга передана на рассмотрение исполнителю');    // Обработка заявления
            case 15: return Yii::t('claim_status', 'Документы не соответствуют оригиналам');          // Обработка заявления

            case 9: return Yii::t('claim_status', 'В процессе отмены');               // (не отображать) Отмена заявления : Возвращается в случае, если процесс отмены заявки занимает время. Только из состояний Черновик и Возврат.
            case 10: return Yii::t('claim_status', 'Отменено');                       // (не отображать) Отмена заявления : Заявление, удалённое пользователем. Только из состояний Черновик и Возврат.
            case 11: return Yii::t('claim_status', 'Неподтвержденная отмена');        // Отмена заявления : Возвращается в случае, если помимо подачи заявки с портала на отмену заявки, требуется дополнительное подтверждение пользователя, которое не было получено, либо пользователь отказался от отмены.

        }
    }
}