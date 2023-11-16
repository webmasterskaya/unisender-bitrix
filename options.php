<?php

/**
 * Bitrix vars
 *
 * @global \CUser     $USER
 * @global \CMain     $APPLICATION
 * @global \CDatabase $DB
 */

use Bitrix\Main\Loader;

\IncludeModuleLangFile(__FILE__);
\IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"] . BX_ROOT . "/modules/main/options.php");

$moduleID = 'webmasterskaya.unisender';

Loader::includeModule($moduleID);

$arTabs = [

];

?>

<?php
require($_SERVER["DOCUMENT_ROOT"] . BX_ROOT . '/modules/main/include/epilog_admin.php');
