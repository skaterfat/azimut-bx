<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true );?>
<?if($arResult['SECTIONS']):?>
		<div class="section-tags hidden-xs">
				<?
				$curDir = $APPLICATION->GetCurDir();
				foreach($arResult['SECTIONS'] as $arSection):
					$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_EDIT"));
					$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_SECTION_DELETE_CONFIRM')));
					if($curDir != $arSection['SECTION_PAGE_URL']) continue;
					?>
						<a href="javascript:;" class="btn btn-default" id="<?=$this->GetEditAreaId($arSection['ID']);?>"><?=$arSection['NAME'];?></a>
				<?endforeach;?>
				<?foreach($arResult['SECTIONS'] as $arSection):
					$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_EDIT"));
					$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_SECTION_DELETE_CONFIRM')));
					if($curDir == $arSection['SECTION_PAGE_URL']) continue;
					?>
						<a href="<?=$arSection['SECTION_PAGE_URL'];?>" class="btn btn-default white" id="<?=$this->GetEditAreaId($arSection['ID']);?>"><?=$arSection['NAME'];?></a>
				<?endforeach;?>
		</div>
		<div class="section-tags-more hidden-xs">
			<a href="javascript:;" data-show-text="Показать еще +" data-hide-text="Скрыть -">Показать еще +</a>
		</div>
<?endif;?>