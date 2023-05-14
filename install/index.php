<?php

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Xetc\Map\ExampleTable;

Loc::loadMessages(__FILE__);

class xetc_map extends CModule
{
    public function __construct()
    {
        $arModuleVersion = array();

        include __DIR__ . '/version.php';

        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion))
        {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }

        $this->MODULE_ID = 'xetc.map';
        $this->MODULE_NAME = Loc::getMessage('XETC_MAP_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('XETC_MAP_MODULE_DESCRIPTION');
        $this->MODULE_GROUP_RIGHTS = 'N';
        $this->PARTNER_NAME = Loc::getMessage('XETC_MAP_MODULE_PARTNER_NAME');
        $this->PARTNER_URI = 'https://xetc.ru';
    }

    public function doInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);
        $this->InstallFiles();
        $this->installDB();
    }

    public function doUninstall()
    {
        $this->UnInstallFiles();
        $this->uninstallDB();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    function InstallFiles()
    {
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/xetc.map/install/components",
            $_SERVER["DOCUMENT_ROOT"]."/bitrix/components", true, true);
        return true;
    }
    public function installDB()
    {
        if (Loader::includeModule($this->MODULE_ID))
        {
            ExampleTable::getEntity()->createDbTable();
        }
    }

    function UnInstallFiles()
    {
        DeleteDirFilesEx("/bitrix/components/xetcmap");
        return true;
    }
    public function uninstallDB()
    {
        if (Loader::includeModule($this->MODULE_ID))
        {
            $connection = Application::getInstance()->getConnection();
            $connection->dropTable(ExampleTable::getTableName());
        }
    }
}
