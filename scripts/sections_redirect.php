<?
@set_time_limit(3600);
@ini_set("memory_limit", "1024M");

//die();

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

if(Loader::includeModule('main') && Loader::includeModule("iblock")) {


	$CIBlockSection = new CIBlockSection;
	$CIBlockElement = new CIBlockElement;
	$IBLOCK_ID = 24;

	$rsProducts = $CIBlockElement->GetList(Array(), Array('IBLOCK_ID' => $IBLOCK_ID, 'SECTION_ID' => 440), false, false, Array());
	while($arP = $rsProducts->GetNext()) {
		echo 'Redirect 301 /catalog/poverhnostnyi-vodootvod/vodootvodnye-lotki/vodootvodnye-lotki-usilennye-s-chugunnymi-reshetkami-c250/', $arP['CODE'], '/ ', $arP['DETAIL_PAGE_URL'], '<br>'; 
	}

	/*$rsProducts = $CIBlockElement->GetList(Array(), Array('IBLOCK_ID' => $IBLOCK_ID, 'SECTION_ID' => 439), false, false, Array());
	while($arP = $rsProducts->GetNext()) {

		$db_old_groups = CIBlockElement::GetElementGroups($arP['ID'], true);
		$arUpdatedGroups = [];
		while($ar_group = $db_old_groups->Fetch())
			if($ar_group["ID"] != 427)
				$arUpdatedGroups[] = $ar_group["ID"];
		CIBlockElement::SetElementSection($arP['ID'], $arUpdatedGroups);

	}

	$db_old_groups = CIBlockElement::GetElementGroups($ELEMENT_ID, true);
	$ar_new_groups = Array($NEW_GROUP_ID);
	while($ar_group = $db_old_groups->Fetch())
		$ar_new_groups[] = $ar_group["ID"];
	CIBlockElement::SetElementSection($ELEMENT_ID, $ar_new_groups);*/


}

echo '</pre>';

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");