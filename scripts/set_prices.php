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
	$CATALOG_SECTIONS = Array();

	$rsCatalogSections = $CIBlockSection->GetList(Array('SORT' => 'ASC', 'NAME' => 'ASC'), Array('IBLOCK_ID' => $IBLOCK_ID, 'ACTIVE' => 'Y'), false, Array('ID', 'NAME', 'XML_ID'));
	while ($arCS = $rsCatalogSections->GetNext()) {
		if($arCS['ID'] > 400)
			$CATALOG_SECTIONS[$arCS['XML_ID']] = $arCS['ID'];
	}

	function createElementRecursive($SECTIONS) {

		global $CIBlockElement;
		global $IBLOCK_ID;
		global $CATALOG_SECTIONS;

		foreach($SECTIONS as $arSection) {


			//Если у этой категории есть подкатегории, то перебираем их и вызываем текущую функцию
			if($arSection['categories']) {

				createElementRecursive($arSection['categories']);

			}
			//Иначе это последняя вложенная категория, если у неё есть элементы, то добавляем их
			elseif($arSection['products'][0]) {

				foreach($arSection['products'] as $arProductGroup) {

					// 446 - Дренажные трубы
					//if($CATALOG_SECTIONS[$arSection['id']] == 446) {
					if($CATALOG_SECTIONS[$arSection['id']] == 500) {

						/*print_r($arSection);
						die();*/

						foreach($arProductGroup as $key => $arProduct) {

							$ELEMENT_NAME = preg_replace('/\s\s+/', ' ', trim($arProduct['name']));
							//$SECTION_CODE = Cutil::translit($SECTION_NAME, "ru", array("replace_space" => "-", "replace_other" => "-"));
							$ELEMENT_CODE = str_ireplace(".html", "", $arProduct['code']);

							$rsElement = $CIBlockElement->GetList(Array(), Array('IBLOCK_ID' => $IBLOCK_ID, 'CODE' => $ELEMENT_NCODE, 'NAME' => $ELEMENT_NAME, 'IBLOCK_SECTION_ID' => 500), false, false, Array('ID', 'NAME'));

							if($arElement = $rsElement->Fetch()) {

								print_r($arProduct);

								if($arProduct['price'] == 'Цена по запросу')
									$PRICE = 0;
								else {
									$PRICE = $arProduct['price'];
								}

								/*if($arProduct['price'] == 'Цена по запросу')
									$PRICE = 0;
								else {
									if($arProduct['measure_ratio'] > 1) {
										echo $arProduct['measure_ratio'], '<br>';
										$PRICE = $arProduct['price'] / 3;
									}
									else
										$PRICE = $arProduct['price'];
								}*/

								//Обновление цены
								$arPriceFields = Array(
									"PRODUCT_ID" => $arElement['ID'],
									"CATALOG_GROUP_ID" => 1,
									"PRICE" => $PRICE,
									"CURRENCY" => "RUB"
								);

								$db_res = CPrice::GetList(Array(), array("PRODUCT_ID" => $arElement['ID'], "CATALOG_GROUP_ID" => 1) );
								if ($ar_res = $db_res->Fetch())
									CPrice::Update($ar_res["ID"], $arPriceFields);
								else
									CPrice::Add($arPriceFields);

							}

						}

					}

				}

			}

		}

	}

	function clearProductsArray($arProducts) {

		$PRODUCTS = Array();

		foreach($arProducts as $i => $value)
			foreach ($value as $j => $v)
				$PRODUCTS[] = $v;

		return $PRODUCTS;

	}

	function getAllProducts($arSections) {

		$arProducts = array();

		foreach($arSections as $arSection) {

			if($arSection['categories']) {

				$arProducts = array_merge_recursive($arProducts, getAllProducts($arSection['categories']));

			}
			elseif($arSection['products']) {

				foreach($arSection['products'] as $key => $arProduct)
					$arProducts[] = $arProduct;

			}

		}

		return $arProducts;

	}


	$PRODUCT_DATA = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"]."/scripts/azimut_catalog.json"), true);

	//print_r($PRODUCT_DATA);

	//createIBlockProperties(clearProductsArray(getAllProducts($PRODUCT_DATA)));

	//createSectionsRecursive($PRODUCT_DATA);

	createElementRecursive($PRODUCT_DATA);
}

echo '</pre>';

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");