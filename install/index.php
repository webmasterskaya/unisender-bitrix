<?php

use Bitrix\Main\EventManager;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);

class webmasterskaya_unisender extends \CModule
{
    var $MODULE_ID = "webmasterskaya.unisender";

    protected array $MODULE_EVENT_HANDLERS
        = [
            'main' => [
                'OnBeforeEventSend' => '\\Webmasterskaya\\Unisender\\Bitrix\\EventHandler::onBeforeEventSend',
            ]
        ];

    public function __construct()
    {
        if (@include_once __DIR__ . '/version.php') {
            $this->MODULE_VERSION      = $arModuleVersion["VERSION"] ?? null;
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"] ?? null;
        }

        $this->MODULE_NAME        = Loc::getMessage('WEBMASTERSKAYA_UNISENDER_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('WEBMASTERSKAYA_UNISENDER_MODULE_DESCRIPTION');
    }

    public function DoInstall()
    {
        global $APPLICATION;

        if ($this->InstallFiles()) {
            $this->InstallDB();
        }

        $APPLICATION->IncludeAdminFile(
            $this->MODULE_NAME . ' - Установка',
            realpath(__DIR__ . '/step.php')
        );
    }

    public function DoUninstall()
    {
        global $APPLICATION;

        if ($this->UnInstallDB()) {
            $this->UnInstallFiles();
        }

        $APPLICATION->IncludeAdminFile(
            $this->MODULE_NAME . ' - Удаление',
            realpath(__DIR__ . '/unstep.php')
        );
    }

    public function InstallFiles(): bool
    {
        global $APPLICATION;
        if (!CopyDirFiles(realpath(__DIR__ . '/admin'), $_SERVER["DOCUMENT_ROOT"] . BX_ROOT . "/admin", true, true)) {
            $APPLICATION->ThrowException('Не удалось запиать файлы в папку <pre>' . BX_ROOT . "/admin" . '</pre>');

            return false;
        }

        if (!CopyDirFiles(realpath(__DIR__ . '/themes'), $_SERVER["DOCUMENT_ROOT"] . BX_ROOT . "/themes", true, true)) {
            $APPLICATION->ThrowException('Не удалось запиать файлы в папку <pre>' . BX_ROOT . "/themes" . '</pre>');

            return false;
        }

        return true;
    }

    public function UnInstallFiles()
    {
        DeleteDirFiles(realpath(__DIR__ . '/admin'), $_SERVER["DOCUMENT_ROOT"] . BX_ROOT . "/admin");
        DeleteDirFiles(realpath(__DIR__ . '/themes'), $_SERVER["DOCUMENT_ROOT"] . BX_ROOT . "/themes");
    }

    public function InstallDB(): bool
    {
        global $DB, $DBType, $APPLICATION;

        $errors = $DB->RunSQLBatch(
            realpath(__DIR__ . '/db/' . mb_strtolower($DBType) . '/install.sql')
        );

        if ($errors !== false) {
            $APPLICATION->ThrowException(implode("", $errors));

            return false;
        }

        ModuleManager::registerModule($this->MODULE_ID);

        try {
            if (!Loader::includeModule($this->MODULE_ID)) {
                return false;
            }
        } catch (LoaderException $e) {
            $APPLICATION->ThrowException($e->getMessage());

            return false;
        }

        $this->installDependencies();

        return true;
    }

    function UnInstallDB(): bool
    {
        global $DB, $DBType, $APPLICATION;

        try {
            if (!Loader::includeModule($this->MODULE_ID)) {
                return false;
            }
        } catch (LoaderException $e) {
            $APPLICATION->ThrowException($e->getMessage());

            return false;
        }

        $errors = $DB->RunSQLBatch(
            realpath(__DIR__ . '/db/' . mb_strtolower($DBType) . '/uninstall.sql')
        );

        if ($errors !== false) {
            $APPLICATION->ThrowException(implode("", $errors));

            return false;
        }

        $this->unInstallDependencies();

        ModuleManager::UnRegisterModule($this->MODULE_ID);

        return true;
    }

    public function installDependencies()
    {
        $this->registerEventHandlers();
        $this->installAgents();
    }

    public function unInstallDependencies()
    {
        $this->unRegisterEventHandlers();
        $this->unInstallAgents();
    }

    public function registerEventHandlers(): void
    {
        $event_manager = EventManager::getInstance();

        foreach ($this->checkEventHandlers() as $event) {
            list($module, $event, $class, $method) = $event;

            $event_manager->registerEventHandler(
                $module,
                $event,
                $this->MODULE_ID,
                $class,
                $method
            );
        }
    }

    public function unRegisterEventHandlers(): void
    {
        $event_manager = EventManager::getInstance();

        foreach ($this->checkEventHandlers(false, false) as $event) {
            list($module, $event, $class, $method) = $event;

            $event_manager->unRegisterEventHandler(
                $module,
                $event,
                $this->MODULE_ID,
                $class,
                $method
            );
        }
    }

    public function installAgents()
    {
        // Агент чтения фалов с треками
//        $this->addAgent(
//            "\\Semena\\Agents\\ReadPosttracksFiles::execute();", "N", 300, 510
//        );
    }

    public function unInstallAgents()
    {
        \CAgent::RemoveModuleAgents($this->MODULE_ID);
    }

    protected function checkEventHandlers(bool $check_modules = true, bool $check_handlers = true): array
    {
        $result = [];

        if (!empty($this->MODULE_EVENT_HANDLERS)) {
            foreach ($this->MODULE_EVENT_HANDLERS as $module => $events) {
                $module = mb_strtolower($module);

                if ($check_modules === true) {
                    try {
                        Loader::includeModule($module);
                    } catch (LoaderException $e) {
                        continue;
                    }
                }

                foreach ($events as $event => $handler) {
                    if (!is_array($handler)) {
                        if (!is_string($handler)) {
                            continue;
                        }

                        if (strpos('::', $handler) !== false) {
                            $handler = explode('::', $handler);
                        } else {
                            continue;
                        }
                    };

                    list($class, $method) = $handler;

                    if ($check_handlers === true) {
                        if (!class_exists($class)) {
                            continue;
                        }

                        if (!method_exists($class, $method)) {
                            continue;
                        }
                    }

                    $result[] = [$module, $event, $class, $method];
                }
            }
        }

        return $result;
    }

    protected function addAgent(
        $name,
        $period = "N",
        int $interval = 86400,
        $sort = 500,
        $active = "Y",
        $datecheck = "",
        $next_exec = "",
        $user_id = false,
        $existError = false
    ) {
        if (is_callable($name)) {
            CAgent::AddAgent(
                $name, $this->MODULE_ID, $period, $interval, $datecheck, $active,
                $next_exec, $sort, $user_id, $existError
            );
        }
    }

}