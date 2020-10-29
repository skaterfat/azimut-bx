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
	$IBLOCK_ID = 24;
	$CATALOG_SECTIONS = Array();

	$rsCatalogSections = $CIBlockSection->GetList(Array('SORT' => 'ASC', 'NAME' => 'ASC'), Array('IBLOCK_ID' => $IBLOCK_ID, 'UF_IS_TAG' => true), false, Array('ID', 'NAME', 'CODE', 'IBLOCK_SECTION_ID', 'SECTION_PAGE_URL'));

	while($arCS = $rsCatalogSections->GetNext()) {

		$CATALOG_SECTIONS[$arCS['ID']] = $arCS;
		$CATALOG_SECTIONS[$arCS['ID']]['PATH'] = [];

		$nav = CIBlockSection::GetNavChain(false, $arCS['ID'], ['ID', 'NAME', 'CODE', 'IBLOCK_SECTION_ID', 'SECTION_PAGE_URL']);
		while($arSectionPath = $nav->GetNext())
			$CATALOG_SECTIONS[$arCS['ID']]['PATH'][] = $arSectionPath;
	}

	$data = ["ID;NAME;URL;PATH;"];

	foreach($CATALOG_SECTIONS as $arSection) {
		$str = $arSection['ID'].';'.$arSection['NAME'].';'.$arSection['SECTION_PAGE_URL'].';';
		if($arSection['PATH']) {
			foreach ($arSection['PATH'] as $path) {
				$str .= $path['NAME'] . " / ";
			}
		}
		$str = rtrim(trim($str, ' '), '/').";";
		$data[] = $str;

		echo "Disallow: " . $arSection['SECTION_PAGE_URL'] . "\n";
	}

	file_put_contents($_SERVER["DOCUMENT_ROOT"]."/scripts/export.csv", implode("\n", $data));

	//print_r($CATALOG_SECTIONS);

	


}

echo '</pre>';

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
