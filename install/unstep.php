<?php

use Bitrix\Main\Localization\Loc;

if (!check_bitrix_sessid()) {
    return;
}

global $APPLICATION;

if ($errorException = $APPLICATION->getException()) {
    // ошибка при удалении модуля
    (new CAdminMessage(
        [
            "MESSAGE" => Loc::getMessage('WEBMASTERSKAYA_UNISENDER_MODULE_NAME') . ' - Ошибка удаления',
            "TYPE"    => "ERROR",
            "DETAILS" => $errorException->GetString()
        ]
    ))->Show();
} else {
    // модуль успешно удалён
    (new CAdminMessage(
        [
            "MESSAGE" => Loc::getMessage('WEBMASTERSKAYA_UNISENDER_MODULE_NAME') . ' - Удаление завершено',
            "TYPE"    => "OK"
        ]
    ))->Show();
}
?>
<form action="<?= $APPLICATION->getCurPage(); ?>">
	<input type="hidden" name="lang" value="<?= LANGUAGE_ID; ?>"/>
	<input type="submit" value="<?= Loc::getMessage('WEBMASTERSKAYA_UNISENDER_TO_MODULES_LIST'); ?>">
</form>