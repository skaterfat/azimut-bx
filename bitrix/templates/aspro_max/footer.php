						<?CMax::checkRestartBuffer();?>
						<?IncludeTemplateLangFile(__FILE__);?>
							<?if(!$isIndex):?>
								<?if($isHideLeftBlock && !$isWidePage):?>
									</div> <?// .maxwidth-theme?>
								<?endif;?>
								</div> <?// .container?>
							<?else:?>
								<?CMax::ShowPageType('indexblocks');?>
							<?endif;?>
							<?CMax::get_banners_position('CONTENT_BOTTOM');?>
						</div> <?// .middle?>
					<?//if(($isIndex && $isShowIndexLeftBlock) || (!$isIndex && !$isHideLeftBlock) && !$isBlog):?>
					<?if(($isIndex && ($isShowIndexLeftBlock || $bActiveTheme)) || (!$isIndex && !$isHideLeftBlock)):?>
						</div> <?// .right_block?>
						<?if($APPLICATION->GetProperty("HIDE_LEFT_BLOCK") != "Y" && !defined("ERROR_404")):?>
							<?CMax::ShowPageType('left_block');?>
						<?endif;?>
					<?endif;?>
					</div> <?// .container_inner?>
				<?if($isIndex):?>
					</div>
				<?elseif(!$isWidePage):?>
					</div> <?// .wrapper_inner?>
				<?endif;?>
			</div> <?// #content?>
			<?CMax::get_banners_position('FOOTER');?>
		</div><?// .wrapper?>

		<footer id="footer">
			<?include_once(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'include/footer_include/under_footer.php'));?>
			<?include_once(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'include/footer_include/top_footer.php'));?>
		</footer>
		<?include_once(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'include/footer_include/bottom_footer.php'));?>

<script>
window.calltouch_request = function(ths){
    var m = jQuery(ths).closest('form');
    if(m.valid()) {
        var fio = m.find('input[name*="FIO"],input[name*="NAME"],input[data-sid*="FIO"],input[data-sid*="NAME"],input[name="ORDER_PROP_1"],input[autocomplete="name"]').val();
        var phone = m.find('input[name*="PHONE"],input[data-sid*="PHONE"],input[name="ORDER_PROP_3"],input[autocomplete="tel"]').val();
        var mail = m.find('input[name*="EMAIL"],input[data-sid*="EMAIL"],input[name="ORDER_PROP_2"],input[autocomplete="email"]').val();
        var ct_site_id = '31967';
        var sub = 'request';
        if(!!m.attr('name')){ sub = m.attr('name').replace(/aspro_/gim, ''); } else { if(!!m.attr('id')){ sub = m.attr('id').replace(/aspro_/gim, ''); }}
        var ct_data = {           
            fio: fio,
            phoneNumber: phone,
            email: mail,
            subject: sub,
            sessionId: window.call_value
        };
        if (!!phone || !!mail){
            jQuery.ajax({
              url: 'https://api-node11.calltouch.ru/calls-service/RestAPI/requests/'+ct_site_id+'/register/',
              dataType: 'json', type: 'POST', data: ct_data, async: false
            });
        }
    }
}
jQuery(document).on("click", 'form button[type="submit"], form input[type="submit"], form a.btn-default', function() { window.calltouch_request(this) });
</script>

	</body>
</html>