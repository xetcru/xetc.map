<?php

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$aMenu = array(
    array(
        'parent_menu' => 'global_menu_services',//в глобальном меню "сервисы"
        'sort' => 400,
        'text' => Loc::getMessage('XETC_MAP_MENU_TITLE'),
        'title' => Loc::getMessage('XETC_MAP_MENU_TITLE'),
        'icon' => 'sale_menu_icon_statisti',
        'page_icon' => 'sale_menu_icon_statisti',
        //'url' => 'xetcmap_index.php',
        'url' => '/bitrix/admin/settings.php?lang=ru&mid=xetc.map&mid_menu=1',
        //'items_id' => 'menu_references',
        'items_id' => 'xetc.map',
        /*'items' => array(
            array(
                'text' => Loc::getMessage('XETC_MAP_SUBMENU_TITLE'),
                'url' => 'xetcmap_index.php?param1=paramval&lang=' . LANGUAGE_ID,
                'more_url' => array('xetcmap_index.php?param1=paramval&lang=' . LANGUAGE_ID),
                'title' => Loc::getMessage('XETC_MAP_SUBMENU_TITLE'),
            ),
        ),//*/
    ),
);

//return $menu;
return (!empty($aMenu) ? $aMenu : false);
