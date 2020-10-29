<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

foreach($arResult['SECTIONS'] as $key => $arSection) {

	if($arResult['SECTION']['DEPTH_LEVEL'] >= 1) {
		if(!$arSection['UF_IS_TAG'])
			unset($arResult['SECTIONS'][$key]);
	}

}