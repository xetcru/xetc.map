<?php

namespace Xetc\Map;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\Validator;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class ExampleTable extends DataManager
{
    public static function getTableName()
    {
        return 'xetc_map_example';
    }

    public static function getMap()
    {
        return array(
            new IntegerField('ID', array(
                'autocomplete' => true,
                'primary' => true,
                'title' => Loc::getMessage('XETC_MAP_ID'),
            )),
            new StringField('NAME', array(
                'required' => true,
                'title' => Loc::getMessage('XETC_MAP_NAME'),
                'default_value' => function () {
                    return Loc::getMessage('XETC_MAP_NAME_DEFAULT_VALUE');
                },
                'validation' => function () {
                    return array(
                        new Validator\Length(null, 255),
                    );
                },
            )),
            new StringField('DATA', array(
                'required' => false,
                'title' => Loc::getMessage('XETC_MAP_DATA'),
                'fetch_data_modification' => function () {
                    return array(
                        function ($value) {
                            if (strlen($value)) {
                                return explode(',', $value);
                            }
                        },
                    );
                },
                'save_data_modification' => function () {
                    return array(
                        function ($value) {
                            if (is_array($value)) {
                                $value = array_filter($value, 'intval');

                                return implode(',', $value);
                            }
                        },
                    );
                },
            )),
        );
    }
}
