<?
@set_time_limit(3600);
@ini_set("memory_limit", "1024M");

die();

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

define("NO_KEEP_STATISTIC", true);
define('BX_SESSION_ID_CHANGE', false);
define('NO_AGENT_CHECK', true);

use \Bitrix\Main,
	Bitrix\Main\Loader,
	Bitrix\Main\Config\Option,
	Bitrix\Sale,
	Bitrix\Catalog,
	Bitrix\Currency\CurrencyManager,
	Bitrix\Main\Context;

echo '<pre>';

if(Loader::includeModule('main') && Loader::includeModule("iblock") && Loader::includeModule("sale") && Loader::includeModule("catalog") && Loader::includeModule("currency")) {

	global $CIBlock;
	global $CIBlockProperty;
	global $CIBlockPropertyEnum;
	global $CIBlockSection;
	global $CIBlockElement;
	global $IBLOCK_ID;
	global $CATALOG_SECTIONS;

	$CIBlock = new CIBlock;
	$CIBlockProperty = new CIBlockProperty;
	$CIBlockPropertyEnum = new CIBlockPropertyEnum;
	$CIBlockSection = new CIBlockSection;
	$CIBlockElement = new CIBlockElement;
	$IBLOCK_ID = 24;

	$rsElements = $CIBlockElement->GetList(Array(), Array('IBLOCK_ID' => $IBLOCK_ID, 'PROPERTY_CML2_ARTICLE' => 'арт.%'), false, false, Array('ID', 'NAME', 'PROPERTY_CML2_ARTICLE'));

	while($arE = $rsElements->Fetch()) {
		CIBlockElement::SetPropertyValuesEx($arE['ID'], $IBLOCK_ID, array('CML2_ARTICLE' => str_ireplace("арт.", "", $arE['PROPERTY_CML2_ARTICLE_VALUE'])));
	}

}

echo '</pre>';

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");