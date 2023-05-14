<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Web\Json;

if (!Loader::includeModule('xetc.map')) {
    ShowError('Module "xetc.map" is not installed');
    return;
}
// Получение параметров компонента
$propertyCodeCoordinates = isset($arParams['JSON_COORDINATES']) ? $arParams['JSON_COORDINATES'] : [];
$apiKey = isset($arParams['API_KEY']) ? $arParams['API_KEY'] : '';
$dir_path = $_SERVER['DOCUMENT_ROOT']."/bitrix/modules/xetc.map/json/";

// Получение координат из json-файлов
if (!empty($propertyCodeCoordinates) && is_array($propertyCodeCoordinates)) {
  $jsonFiles = $propertyCodeCoordinates;

  $coordinates = array();
  foreach ($jsonFiles as $jsonFile) {
    if (file_exists($dir_path . $jsonFile)) {
      $coordinates[] = $jsonFile;
    }
  }
} else {
  ShowError('No coordinates were specified');
}
// Формирование параметров шаблона компонента
$arResult = [
    'API_KEY' => $apiKey,
    'COORDINATES' => $coordinates,
    'URL' => $dir_path,
];
$this->IncludeComponentTemplate();
