<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$APPLICATION->IncludeComponent(
	"bitrix:map.yandex.view",
	"map",
	Array(
		"API_KEY" => "",
		"CONTROLS" => array("ZOOM","TYPECONTROL","SCALELINE"),
		"INIT_MAP_TYPE" => "MAP",
		"MAP_DATA" => "a:4:{s:10:\"yandex_lat\";d:55.63576863352686;s:10:\"yandex_lon\";d:37.841555794424984;s:12:\"yandex_scale\";i:17;s:10:\"PLACEMARKS\";a:2:{i:0;a:3:{s:3:\"LON\";d:37.838787336686;s:3:\"LAT\";d:55.641984981288;s:4:\"TEXT\";s:28:\"Офис ГК \"АЗИМУТ\"\";}i:1;a:3:{s:3:\"LON\";d:37.836941976884;s:3:\"LAT\";d:55.640694004161;s:4:\"TEXT\";s:30:\"Склад ГК \"АЗИМУТ\"\";}}}",
		"MAP_HEIGHT" => "100%",
		"MAP_ID" => "",
		"MAP_WIDTH" => "100%",
		"OPTIONS" => array("ENABLE_DBLCLICK_ZOOM","ENABLE_DRAGGING"),
		"USE_REGION_DATA" => "Y"
	)
);?>