<?
/*
Скрипт наполняет теговые разделы всеми товарами родительского раздела
*/
@set_time_limit(7200);
@ini_set("memory_limit", "1024M");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

define("NO_KEEP_STATISTIC", true);
define('BX_SESSION_ID_CHANGE', false);
define('NO_AGENT_CHECK', true);

use \Bitrix\Main,
	\Bitrix\Main\Loader;


echo '<pre>';

if(Loader::includeModule('main') && Loader::includeModule("iblock")) {

	$IBLOCK_ID = 24;

	$CIBlockSection = new CIBlockSection;
	$CIBlockElement = new CIBlockElement;

	$rsSections = $CIBlockSection->GetList(Array(), Array('IBLOCK_ID' => $IBLOCK_ID, 'ACTIVE' => 'Y', 'UF_IS_TAG' => true), false, Array('ID', 'NAME', 'IBLOCK_SECTION_ID'));

	$arTagsSections = Array();

	while ($arS = $rsSections->Fetch()) {
		$arTagsSections[] = $arS;
	}

	//print_r($arTagsSections);
	//IBLOCK_SECTION_ID основной раздел элемента
	//IBLOCK_SECTION - массив разделов к которым привязан элемент

	foreach ($arTagsSections as $key => $arTagSect) {

		$rsElements = $CIBlockElement->GetList(Array(), Array('IBLOCK_ID' => $IBLOCK_ID, 'IBLOCK_SECTION_ID' => $arTagSect['IBLOCK_SECTION_ID']), false, false, Array('ID', 'NAME'));

		while($arE = $rsElements->Fetch()) {

			/*print_r($arTagSect);
			print_r($arE);*/

			$db_old_groups = CIBlockElement::GetElementGroups($arE['ID'], true);
			$ar_new_groups = Array($arTagSect['ID']);
			while($ar_group = $db_old_groups->Fetch())
			    $ar_new_groups[] = $ar_group["ID"];

			//print_r($ar_new_groups);

			CIBlockElement::SetElementSection($arE['ID'], $ar_new_groups);
			//die();

		}


	}

}

echo '</pre>';


require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");