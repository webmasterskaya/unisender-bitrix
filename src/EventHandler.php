<?php

namespace Webmasterskaya\Unisender\Bitrix;

use Bitrix\Main\Mail\Context;

class EventHandler
{
    /**
     * @param   array                      $arFields
     * @param   array                      $eventMessage
     * @param   \Bitrix\Main\Mail\Context  $context
     * @param   array                      $arResult
     *
     * @return bool
     */
    public static function onBeforeEventSend(array &$arFields, array &$eventMessage, Context &$context, array &$arResult): bool
    {
        return true;
    }
}