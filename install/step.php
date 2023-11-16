<?php

use Bitrix\Main\Localization\Loc;

if (!check_bitrix_sessid()) {
    return;
}

global $APPLICATION;

if ($errorException = $APPLICATION->getException()) {
    // ошибка при установке модуля
    (new CAdminMessage(
        [
            "MESSAGE" => Loc::getMessage('WEBMASTERSKAYA_UNISENDER_MODULE_NAME') . ' - Ошибка установки',
            "TYPE"    => "ERROR",
            "DETAILS" => $errorException->GetString()
        ]
    ))->Show();
} else {
    // модуль успешно установлен
    (new CAdminMessage(
        [
            "MESSAGE" => Loc::getMessage('WEBMASTERSKAYA_UNISENDER_MODULE_NAME') . ' - Установка завершена',
            "TYPE"    => "OK"
        ]
    ))->Show();
}
?>
<form action="<?= $APPLICATION->getCurPage(); ?>">
	<input type="hidden" name="lang" value="<?= LANGUAGE_ID; ?>"/>
	<input type="submit" value="<?= Loc::getMessage('WEBMASTERSKAYA_UNISENDER_TO_MODULES_LIST'); ?>">
</form>