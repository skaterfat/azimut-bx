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
	global $IBLOCK_PROPERTIES;
	global $CATALOG_SECTIONS;

	$CIBlock = new CIBlock;
	$CIBlockProperty = new CIBlockProperty;
	$CIBlockPropertyEnum = new CIBlockPropertyEnum;
	$CIBlockSection = new CIBlockSection;
	$CIBlockElement = new CIBlockElement;
	$IBLOCK_ID = 24;
	$IBLOCK_PROPERTIES = Array();
	$CATALOG_SECTIONS = Array();

	$rsIblockProps = $CIBlockProperty->GetList(Array("sort" => "asc", "name" => "asc"), Array("ACTIVE" => "Y", "IBLOCK_ID" => $IBLOCK_ID));
	while ($arProp = $rsIblockProps->GetNext()) {

		if($arProp['SORT'] >= 5000 || $arProp['CODE'] == 'BRAND') {

			$IBLOCK_PROPERTIES[$arProp['CODE']] = Array(
				"ID" => $arProp['ID'],
				"CODE" => $arProp['CODE'],
				"NAME" => $arProp['NAME'],
				"PROPERTY_TYPE" => $arProp['PROPERTY_TYPE'],
				"MULTIPLE" => $arProp['MULTIPLE']
			);

			if($arProp['PROPERTY_TYPE'] == 'L') {
				$rsPropertyEnums = $CIBlockPropertyEnum->GetList(Array("DEF" => "DESC", "SORT" => "ASC"), Array("IBLOCK_ID" => $IBLOCK_ID, "CODE" => $arProp['CODE']));
				$IBLOCK_PROPERTIES[$arProp['CODE']]['VALUES'] = Array();
				while($enum_fields = $rsPropertyEnums->GetNext())
					$IBLOCK_PROPERTIES[$arProp['CODE']]['VALUES'][$enum_fields["ID"]] = $enum_fields["VALUE"];
			}

			if($arProp['CODE'] == 'BRAND')
				$IBLOCK_PROPERTIES[$arProp['CODE']]['VALUES'] = Array(
					682 => "Gidrolica",
					683 => "ГК АЗИМУТ",
					684 => "Россия",
					685 => "ПЕРФОКОР",
					686 => "Польша",
					687 => "FD",
					688 => "КОРСИС",
					689 => "AVK",
					690 => "Hawle",
					691 => "JAFAR",
					692 => "VAG"
				);

		}

	}

	$rsCatalogSections = $CIBlockSection->GetList(Array('SORT' => 'ASC', 'NAME' => 'ASC'), Array('IBLOCK_ID' => $IBLOCK_ID, 'ACTIVE' => 'Y'), false, Array('ID', 'NAME', 'XML_ID'));
	while ($arCS = $rsCatalogSections->GetNext()) {
		if($arCS['ID'] > 400)
			$CATALOG_SECTIONS[$arCS['XML_ID']] = $arCS['ID'];
	}

	function createSectionsRecursive($SECTIONS, $PARENT_SECTION_ID = false) {

		global $CIBlockSection;
		global $IBLOCK_ID;

		$SECTION_SORT = 100;

		foreach($SECTIONS as $arSection) {

			//print_r($IBLOCK_PROPERTIES);
			//print_r($arSection);
			//die();

			$SECTION_NAME = preg_replace('/\s\s+/', ' ', trim($arSection['name']));
			//$SECTION_CODE = Cutil::translit($SECTION_NAME, "ru", array("replace_space" => "-", "replace_other" => "-"));
			$SECTION_CODE = $arSection['code'];
			$XML_ID = $arSection['id'];

			//Создаем категорию, переданную как параметр (является родительской)
			$arSectionFields = Array(
				'IBLOCK_ID' => $IBLOCK_ID,
				'ACTIVE' => 'Y',
				'NAME' => $SECTION_NAME,
				'CODE' => $SECTION_CODE,
				'SORT' => $SECTION_SORT,
				'DESCRIPTION_TYPE' => 'html',
				'DESCRIPTION' => trim($arSection['description']),
				'XML_ID' => $XML_ID
			);

			if($PARENT_SECTION_ID)
				$arSectionFields['IBLOCK_SECTION_ID'] = $PARENT_SECTION_ID;

			if($arSection['picture'] && file_exists($_SERVER['DOCUMENT_ROOT'].'/scripts'.$arSection['picture']))
				$arSectionFields['PICTURE'] = CFile::MakeFileArray($_SERVER['DOCUMENT_ROOT'].'/scripts'.$arSection['picture']);

			/*print_r($arSectionFields);
			die();*/

			$NEW_SECTION_ID = $CIBlockSection->Add($arSectionFields);

			if(intval($NEW_SECTION_ID) > 0) {

				$SECTION_SORT += 100;

				echo 'Redirect 301 '.$arSection['link'].' '.str_ireplace('/shop/', '/catalog/', $arSection['link']), '/<br>';

				if($arSection['categories'])
					createSectionsRecursive($arSection['categories'], $NEW_SECTION_ID); //Второй параметр родительская категория

			}
			else {
				echo 'Ошибка создания раздела: ', $CIBlockSection->LAST_ERROR, print_r($arSectionFields, true), '<br>';
			}


		}

	}


	function createElementRecursive($SECTIONS) {

		global $CIBlockElement;
		global $IBLOCK_ID;
		global $IBLOCK_PROPERTIES;
		global $CATALOG_SECTIONS;

		die();

		foreach($SECTIONS as $arSection) {


			//Если у этой категории есть подкатегории, то перебираем их и вызываем текущую функцию
			if($arSection['categories']) {

				createElementRecursive($arSection['categories']);

			}
			//Иначе это последняя вложенная категория, если у неё есть элементы, то добавляем их
			elseif($arSection['products'][0]) {

				$ELEMENT_SORT = 500;

				foreach($arSection['products'] as $arProductGroup) {

					foreach($arProductGroup as $key => $arProduct) {

						//if(!$arProduct['videos']) continue;
						//if($arProduct['price'] != 'Цена по запросу') continue;
						//if(count($arProduct['pictures']) <= 1) continue;

						$PROPS = Array();

						if($arProduct['article'])
							$PROPS['CML2_ARTICLE'] = $arProduct['article'];

						if($arProduct['application_area']) {
							$PROPS['APPLICATION_AREA'] = Array();
							foreach($arProduct['application_area'] as $area)
								if(array_search($area, $IBLOCK_PROPERTIES['APPLICATION_AREA']['VALUES']))
									$PROPS['APPLICATION_AREA'][] = array_search($area, $IBLOCK_PROPERTIES['APPLICATION_AREA']['VALUES']);
						}

						if($arProduct['videos']) {
							$partsVideoUrl = parse_url($arProduct['videos'][0]);
							parse_str($partsVideoUrl['query'], $queryVideo);
							if($queryVideo['v'])
								$PROPS['POPUP_VIDEO'] = 'https://www.youtube.com/embed/'.$queryVideo['v'];
							//if(count() > 1) VIDEO_YOUTUBE
						}

						if($arProduct['comments'])
							$PROPS['FORUM_MESSAGE_CNT'] = count($arProduct['comments']);

						$ELEMENT_NAME = preg_replace('/\s\s+/', ' ', trim($arProduct['name']));
						//$SECTION_CODE = Cutil::translit($SECTION_NAME, "ru", array("replace_space" => "-", "replace_other" => "-"));
						$ELEMENT_CODE = str_ireplace(".html", "", $arProduct['code']);

						$PREVIEW_PICTURE = $DETAIL_PICTURE = "";

						if($arProduct['pictures']) {

							if(file_exists($_SERVER['DOCUMENT_ROOT'].'/scripts/images/'.$arProduct['pictures'][0]))
								$PREVIEW_PICTURE = $DETAIL_PICTURE = CFile::MakeFileArray($_SERVER['DOCUMENT_ROOT'].'/scripts/images/'.$arProduct['pictures'][0]);

							if(count($arProduct['pictures']) > 1) {
								$PROPS['MORE_PHOTO'] = Array();
								$PROPS['PHOTO_GALLERY'] = Array();
								foreach($arProduct['pictures'] as $pkey => $picture) {
									if($pkey > 0 && file_exists($_SERVER['DOCUMENT_ROOT'].'/scripts/images/'.$picture))
										$PROPS['MORE_PHOTO'][] = CFile::MakeFileArray($_SERVER['DOCUMENT_ROOT'].'/scripts/images/'.$picture);
									/*if(file_exists($_SERVER['DOCUMENT_ROOT'].'/scripts/images/'.$picture))
										$PROPS['PHOTO_GALLERY'][] = CFile::MakeFileArray($_SERVER['DOCUMENT_ROOT'].'/scripts/images/'.$picture);*/
								}
							}

						}

						if($arProduct['properties']) {

							foreach($arProduct['properties'] as $prop) {

								$CODE = strtoupper(Cutil::translit($prop['param'], "ru", array("replace_space" => "_", "replace_other" => "_")));
								$VALUE = str_ireplace(" , ", ", ", $prop['value']);

								if(isset($IBLOCK_PROPERTIES[$CODE])) {

									if($IBLOCK_PROPERTIES[$CODE]['PROPERTY_TYPE'] == 'L') {

										if($IBLOCK_PROPERTIES[$CODE]['MULTIPLE'] == 'Y') {

											if(stripos($VALUE, ", ") !== false)
												$VALUE = explode(", ", $VALUE);

											$PROPS[$CODE] = Array();

											if(is_array($VALUE)) {
												foreach($VALUE as $pv)
													if(in_array($pv, $IBLOCK_PROPERTIES[$CODE]['VALUES']))
														$PROPS[$CODE][] = array_search($pv, $IBLOCK_PROPERTIES[$CODE]['VALUES']);
											}
											else
												$PROPS[$CODE] = array_search($VALUE, $IBLOCK_PROPERTIES[$CODE]['VALUES']);

										}
										else {
											$PROPS[$CODE] = array_search($VALUE, $IBLOCK_PROPERTIES[$CODE]['VALUES']);
										}

									}
									elseif($IBLOCK_PROPERTIES[$CODE]['PROPERTY_TYPE'] == 'N') {
										$PROPS[$CODE] = preg_replace('/[^0-9\.,]/', '', trim($VALUE, '.'));
									}
									else {
										$PROPS[$CODE] = $VALUE;
										if($CODE == 'PROIZVODITEL')
											$PROPS['BRAND'] = array_search($VALUE, $IBLOCK_PROPERTIES['BRAND']['VALUES']);
									}


								}

							}

						}

						$arElementFields = Array(
							'IBLOCK_ID' => $IBLOCK_ID,
							'ACTIVE' => 'Y',
							'IBLOCK_SECTION_ID' => $CATALOG_SECTIONS[$arSection['id']],
							'NAME' => $ELEMENT_NAME,
							'CODE' => $ELEMENT_CODE,
							'SORT' => $ELEMENT_SORT,
							"PREVIEW_PICTURE" => $PREVIEW_PICTURE,
							"DETAIL_PICTURE" => $DETAIL_PICTURE,
							'DETAIL_TEXT_TYPE' => 'html',
							'DETAIL_TEXT' => $arProduct['description'],
							'PROPERTY_VALUES' => $PROPS
						);

						$NEW_ELEMENT_ID = $CIBlockElement->Add($arElementFields);

						if(intval($NEW_ELEMENT_ID) > 0) {

							echo 'Redirect 301 '.$arProduct['link'].' '.str_ireplace("kupit-", "", str_ireplace('/shop/', '/catalog/', rtrim($arProduct['link'], '.html'))), '/<br>';

							$arProductFields = Array(
								"ID" => $NEW_ELEMENT_ID,
								"PURCHASING_CURRENCY" => "RUB",
								"QUANTITY" => $arProduct['price'] == 'Цена по запросу' ? 0 : 1000,
								"RATIO" => $arProduct['measure_ratio'] ? $arProduct['measure_ratio'] : 1
							);

							if(CCatalogProduct::Add($arProductFields))
								;//echo "Добавили параметры товара к элементу каталога ".$NEW_ELEMENT_ID.'<br>', $arProductFields['RATIO'];
							else
								echo 'Ошибка добавления параметров для товара '.$NEW_ELEMENT_ID.'<br>';

							if($arProductFields['RATIO'] > 1)
								CCatalogMeasureRatio::add(Array('PRODUCT_ID' => $NEW_ELEMENT_ID, 'RATIO' => $arProductFields['RATIO']));

							if($arProduct['price'] == 'Цена по запросу')
								$PRICE = 0;
							else {
								if($arProduct['measure_ratio'] > 1)
									$PRICE = $arProduct['price'] / 3;
								else
									$PRICE = $arProduct['price'];
							}

							//Обновление цены
							$arPriceFields = Array(
								"PRODUCT_ID" => $NEW_ELEMENT_ID,
								"CATALOG_GROUP_ID" => 1,
								"PRICE" => $PRICE,
								"CURRENCY" => "RUB"
							);

							$db_res = CPrice::GetList(Array(), array("PRODUCT_ID" => $NEW_ELEMENT_ID, "CATALOG_GROUP_ID" => 1) );
							if ($ar_res = $db_res->Fetch())
								CPrice::Update($ar_res["ID"], $arPriceFields);
							else
								CPrice::Add($arPriceFields);

						}
						else
							echo 'Ошибка создания элемента: ', $CIBlockElement->LAST_ERROR, print_r($arElementFields, true), '<br>';

						/*print_r($arElementFields);
						print_r($arProduct);
						die();*/

					}

				}

			}

		}

	}

	function createSimilarProducts($SECTIONS) {

		global $CIBlockElement;
		global $IBLOCK_ID;
		global $IBLOCK_PROPERTIES;
		global $CATALOG_SECTIONS;

		foreach($SECTIONS as $arSection) {

			if($arSection['categories']) {
				createSimilarProducts($arSection['categories']);
			}
			elseif($arSection['products'][0]) {

				foreach($arSection['products'] as $arProductGroup) {

					foreach($arProductGroup as $key => $arProduct) {

						$ELEMENT_CODE = str_ireplace(".html", "", $arProduct['code']);

						if($arProduct['similar_products']) {

							$rsMainProduct = $CIBlockElement->GetList(Array(), Array('IBLOCK_ID' => $IBLOCK_ID, 'CODE' => $ELEMENT_CODE), false, false, Array('ID', 'NAME'));
							$rsSimilarProducts = $CIBlockElement->GetList(Array(), Array('IBLOCK_ID' => $IBLOCK_ID, 'PROPERTY_CML2_ARTICLE' => $arProduct['similar_products']), false, false, Array('ID'));

							$arSimilarId = Array();

							while($arS = $rsSimilarProducts->Fetch())
								$arSimilarId[] = $arS['ID'];

							/*if(!$arSimilarId) {
								print_r($ELEMENT_CODE);
								print_r($arProduct['similar_products']);
							}*/

							if($arSimilarId) {

								while($arMainProduct = $rsMainProduct->Fetch()) {

									CIBlockElement::SetPropertyValuesEx($arMainProduct['ID'], $IBLOCK_ID, Array('EXPANDABLES' => $arSimilarId));

								}

								echo $ELEMENT_CODE, '<br>';

							}

							//echo $rsMainProduct->SelectedRowsCount(), ' ('.$ELEMENT_CODE.')<br>';
							//print_r($arProduct['similar_products']);



						}

						/*$PROPS['CML2_ARTICLE'] = $arProduct['article'];
						/*$PROPS['EXPANDABLES'] = $arProduct['article'];
						str_ireplace("kupit-", "", str_ireplace('/shop/', '/catalog/', rtrim($arProduct['link'], '.html')))*/

					}

				}

			}

		}

	}

	function createReviewsProducts($SECTIONS) {

		global $CIBlockElement;
		global $IBLOCK_ID;
		global $IBLOCK_PROPERTIES;
		global $CATALOG_SECTIONS;

		foreach($SECTIONS as $arSection) {

			if($arSection['categories']) {
				createReviewsProducts($arSection['categories']);
			}
			elseif($arSection['products']) {

				foreach($arSection['products'] as $arProductGroup) {

					foreach($arProductGroup as $key => $arProduct) {

						$ELEMENT_CODE = str_ireplace(".html", "", $arProduct['code']);

						if($arProduct['comments']) {

							print_r($arProduct['comments']);

							//$rsMainProduct = $CIBlockElement->GetList(Array(), Array('IBLOCK_ID' => $IBLOCK_ID, 'CODE' => $ELEMENT_CODE), false, false, Array('ID', 'NAME'));



						}

					}

				}

			}

		}

	}

	//Функция создания/редактирования свойств
	function createIBlockProperties($arProducts) {

		global $CIBlockProperty;
		global $IBLOCK_ID;

		$PROPERTIES = Array();

		foreach($arProducts as $PRODUCT) {

			foreach($PRODUCT['properties'] as $prop) {

				$CODE = strtoupper(Cutil::translit($prop['param'], "ru", array("replace_space" => "_", "replace_other" => "_")));
				$VALUE = str_ireplace(" , ", ", ", $prop['value']);

				if(!isset($PROPERTIES[$CODE]))
					$PROPERTIES[$CODE] = Array('NAME' => $prop['param'], 'VALUES' => Array());

				if(!in_array($VALUE, $PROPERTIES[$CODE]['VALUES']))
					$PROPERTIES[$CODE]['VALUES'][] = $VALUE;

			}

		}

		print_r($PROPERTIES);
		die();

		/*
		TIP_TOVARA
		GIDRAVLICHESKOE_SECHENIE
		KLASS_NAGRUZKI
		KOMPLEKT
		TIP_TRUBY
		DIAMETR
		KOLTSEVAYA_ZHESTKOST
		FILTRATSIYA
		DIAMETR_PODKLYUCHENIYA
		SPOSOB_VODOOTVEDENIYA
		NOMINALNOE_DAVLENIE_PN
		MATERIAL_KORPUSA
		MATERIAL_KRYSHKI
		MATERIAL_SHPINDELYA
		MATERIAL_KLINA
		MATERIAL_UPLOTNENIYA
		MATERIAL_KLAPANA
		MATERIAL_SHIBERA
		MATERIAL_OPORNOGO_BLOKA
		MATERIAL_VALA
		MATERIAL_DISKA
		ISPOLNENIE
		AVTOZAPUSK
		 */

		foreach($PROPERTIES as $CODE => $PROPERTY) {

			if($CODE == 'ISPOLNENIE') {

				$arFields = Array(
					"PROPERTY_TYPE" => "L", //Тип свойства. Возможные значения: S - строка, N - число, F - файл, L - список, E - привязка к элементам, G - привязка к группам.
					"MULTIPLE" => "Y",
					"LIST_TYPE" => "C", //Тип для свойства список (L). Может быть "L" - выпадающий список или "C" - флажки.
					"IBLOCK_ID" => $IBLOCK_ID,
					"VALUES" => Array()
				);

				$NEW_VALUES = Array();

				foreach($PROPERTY['VALUES'] as $VALUE) {

					if(stripos($VALUE, ", ") !== false) {

						$AR_VALUES = explode(", ", $VALUE);

						foreach($AR_VALUES as $V)
							if(!in_array($V, $NEW_VALUES))
								$NEW_VALUES[] = $V;

					}
					elseif(!in_array($VALUE, $NEW_VALUES))
						$NEW_VALUES[] = $VALUE;

				}

				$PROPVALSORT = 100;

				asort($NEW_VALUES);

				foreach($NEW_VALUES as $NV) {
					$arFields["VALUES"][] = Array(
						"VALUE" => $NV,
						"DEF" => "N",
						"SORT" => $PROPVALSORT
					);
					$PROPVALSORT += 100;
				}

				//$CIBlockProperty->Update(695, $arFields);
				//print_r($arFields);

			}

		}

		/*$SORT = 5000;

		foreach($PROPERTIES as $CODE => $PROPERTY) {

			$arFields = Array(
				"NAME" => $PROPERTY['NAME'],
				"ACTIVE" => "Y",
				"SORT" => $SORT,
				"CODE" => $CODE,
				"PROPERTY_TYPE" => "S",
				"IBLOCK_ID" => 24
			);

			$SORT += 10;

			if($NEW_PROP_ID = $CIBlockProperty->Add($arFields))
				echo 'Свойство '.$CODE.' добавлено: ', $NEW_PROP_ID, '<br>';
			else
				echo 'Ошибка добавления свойства '.$CODE.': ', $CIBlockProperty->LAST_ERROR, '<br>';

		}*/
		print_r($PROPERTIES);

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

	//createElementRecursive($PRODUCT_DATA);

	//createSimilarProducts($PRODUCT_DATA);

	createReviewsProducts($PRODUCT_DATA);

}

echo '</pre>';

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");