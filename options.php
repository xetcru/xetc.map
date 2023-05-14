<?php
use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;

defined('ADMIN_MODULE_NAME') or define('ADMIN_MODULE_NAME', 'xetc.map');

if (!$USER->isAdmin()) {
    $APPLICATION->authForm('Nope!');
}

$module_id = "xetc.map";
$dirPath = $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/".$module_id."/json/";

// Функция сохранения изменений в файле
function saveFile($fileName, $postData)
{
    $jsonData = array(
        "name" => $postData["name"],
        "color" => $postData["color"],
        "coords" => array_map(function($coord) {
            return array_map(function($val) {
                return floatval($val);
            }, explode(",", $coord));
        }, explode(",", $postData["coords"]))
    );

    $jsonString = json_encode($jsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents($fileName, $jsonString);
}

// Обработка сохранения изменений
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["save"])) {
    $fileName = $dirPath . $_POST["file"];
    saveFile($fileName, $_POST);
}

// Обработка добавления нового полигона
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add"])) {
    $fileCount = count(glob($dirPath . "polygon_*.json"));
    $fileName = "polygon_" . ($fileCount + 1) . ".json";
    $content = [
        "name" => $_POST["name"],
        "color" => $_POST["color"],
        "coords" => json_decode($_POST["coords"], true),
    ];
    $file = fopen($dirPath . $fileName, "w");
    fwrite($file, json_encode($content));
    fclose($file);
    $lastFile = $fileName;
}

$files = glob($dirPath . "*.json");

?>

<div class="adm-detail-content-item-block">
    <h1><?=GetMessage("XETC_MAP_OPTIONS_TITLE")?></h1>

    <table class="wp-list-table widefat fixed striped posts">
        <thead>
        <tr>
            <th>Имя файла</th>
            <th>Имя</th>
            <th>Цвет</th>
            <th>Координаты</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        <? foreach($files as $jsonFile):?>
            <? $jsonData = json_decode(file_get_contents($jsonFile), true); ?>
            <tr>
                <td><?= basename($jsonFile) ?></td>
                <td><input type="text" name="name[]" value="<?= $jsonData["name"] ?>" /></td>
                <td><input type="text" name="color[]" value="<?= $jsonData["color"] ?>" /></td>
                <td><textarea name="coords[]"><?= implode(",", array_map(function($coord) { return implode(",", $coord); }, $jsonData["coords"])) ?></textarea></td>
                <td><button class="button save-file" data-file="<?= basename($jsonFile) ?>">Сохранить</button></td>
            </tr>
        <? endforeach;?>
        </tbody>
    </table>
    <div class="adm-detail-content-item-block">
        <form id="add-polygon-form" method="post" action="">
            <?=bitrix_sessid_post()?>
            <h3>Добавить полигон</h3>
            <table class="edit-table">
                <tbody>
                <tr>
                    <td>Имя:</td>
                    <td><input type="text" name="name" value=""></td>
                </tr>
                <tr>
                    <td>Цвет:</td>
                    <td><input type="color" name="color" value=""></td>
                </tr>
                <tr>
                    <td>Координаты:</td>
                    <td><input type="text" name="coords" value=""></td>
                </tr>
                </tbody>
            </table>
            <input type="submit" name="add" value="Добавить">
            <?php if (!empty($lastFile)): ?>
                <p>Последний созданный: <?= $lastFile ?></p>
            <?php endif; ?>
        </form>
    </div>
</div>
<script>
    // Обработчик нажатия на кнопку "Сохранить"
    document.querySelectorAll(".save-file").forEach(function(button) {
        button.addEventListener("click", function() {
            var row = this.closest("tr");
            var formData = new FormData();
            formData.append("file", this.dataset.file);
            formData.append("name", row.querySelector("input[name='name[]']").value);
            formData.append("color", row.querySelector("input[name='color[]']").value);
            formData.append("coords", row.querySelector("textarea[name='coords[]']").value);
            formData.append("save", true);

            fetch(window.location.href, { method: "POST", body: formData })
                .then(function(response) {
                    if (response.ok) {
                        alert("Изменения сохранены");
                    } else {
                        alert("Ошибка сохранения");
                    }
                })
                .catch(function(error) {
                    alert("Ошибка сохранения");
                });
        });
    });
</script>
