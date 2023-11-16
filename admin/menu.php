<?php

use Bitrix\Main\Loader;

/**
 * @global \CMain $APPLICATION
 */

$ModuleID = 'webmasterskaya.unisender';

IncludeModuleLangFile(__FILE__);

if (Loader::includeModule($ModuleID) && $APPLICATION->GetGroupRight($ModuleID) >= 'R') {

    $aMenu = array(
        'parent_menu' => 'global_menu_services',
        'section'     => 'webmasterskaya_unisender',
        'sort'        => 10,
        'text'        => 'Юнисендер',
        'icon'        => 'webmasterskaya_unisender_icon_main',
        'items_id'    => 'webmasterskaya_unisender_submenu',
        'items'       => [
            [
                'text'     => 'Почтовые события',
                'url'      => '/bitrix/admin/webmasterskaya_unisender_events.php?lang=' . LANGUAGE_ID,
                'more_url' => [
                    '/bitrix/admin/webmasterskaya_unisender_events.php',
                    '/bitrix/modules/webmasterskaya.unisender/admin/events.php',
                ],
//                'icon' => 'webdebug_reviews_icon_17',
            ],
        ],
    );

    return $aMenu;
}