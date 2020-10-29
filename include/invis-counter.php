<meta name="google-site-verification" content="RE098g-aasJ2QeAKOyNJeo2bViTZJ-YZUWblBzM9A5I" />
<meta name="yandex-verification" content="d79c3b51fad28506" />
<!-- Yandex.Metrika counter --> <script type="text/javascript" > (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)}; m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)}) (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym"); ym(149718, "init", { id:149718, clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true }); </script> <noscript><div><img src="https://mc.yandex.ru/watch/149718" style="position:absolute; left:-9999px;" alt=""  class="img-responsive" ></div></noscript> <!-- /Yandex.Metrika counter -->
<script type="text/javascript">
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-77935317-1', 'auto');
  ga('send', 'pageview');

</script>
<script type="text/javascript">
var __cs = __cs || [];
__cs.push(["setCsAccount", "_7RXLiZ8Ggqdmi_ID2AdxggYh_rlT2UT"]);
</script>
<script type="text/javascript" async src="https://app.comagic.ru/static/cs.min.js"></script>
<meta name="yandex-verification" content="d79c3b51fad28506" />
<meta name="google-site-verification" content="RE098g-aasJ2QeAKOyNJeo2bViTZJ-YZUWblBzM9A5I" />
<meta name="google-site-verification" content="RE098g-aasJ2QeAKOyNJeo2bViTZJ-YZUWblBzM9A5I" />
<meta name="google-site-verification" content="RE098g-aasJ2QeAKOyNJeo2bViTZJ-YZUWblBzM9A5I" />
<script>
$(document).on("click", 'button[type="submit"]', function() { 
var m = $(this).closest('form[name="userform"]');  
var fio = m.find('input[placeholder*="имя"]').val();
var phone = m.find('input[placeholder*="телефон"],input[placeholder*="Телефон"]').val();  
var comment = m.find('textarea[placeholder="Комментарий"]').val();
var ct_site_id = '31967';
var sub = 'Заявка';
var ct_data = {  
fio: fio,          
phoneNumber: phone,
comment: comment,
subject: sub,
sessionId: window.call_value
};
if (!!phone){
$.ajax({  
  url: 'https://api-node11.calltouch.ru/calls-service/RestAPI/requests/'+ct_site_id+'/register/', 
  dataType: 'json', type: 'POST', data: ct_data, async: false
}); 
}
});
</script>

<script>
$(document).on("click", 'button[type="submit"]', function() { 
var m = $('#cart_form');  
var fio = m.find('input[name="name"]').val();
var phone = m.find('input[name="phone"]').val();  
var mail = m.find('input[name="email"]').val();
var comment = m.find('textarea[name="address"]').val();
var ct_site_id = '31967';
var sub = 'Корзина';
var ct_data = {  
fio: fio,          
phoneNumber: phone,
email: mail,
comment: comment,
subject: sub,
sessionId: window.call_value
};
if (!!phone){
$.ajax({  
  url: 'https://api-node11.calltouch.ru/calls-service/RestAPI/requests/'+ct_site_id+'/register/', 
  dataType: 'json', type: 'POST', data: ct_data, async: false
}); 
}
});
</script>

<!-- calltouch -->
<script type="text/javascript">
(function(w,d,n,c){w.CalltouchDataObject=n;w[n]=function(){w[n]["callbacks"].push(arguments)};if(!w[n]["callbacks"]){w[n]["callbacks"]=[]}w[n]["loaded"]=false;if(typeof c!=="object"){c=[c]}w[n]["counters"]=c;for(var i=0;i<c.length;i+=1){p(c[i])}function p(cId){var a=d.getElementsByTagName("script")[0],s=d.createElement("script"),i=function(){a.parentNode.insertBefore(s,a)};s.type="text/javascript";s.async=true;s.src="https://mod.calltouch.ru/init.js?id="+cId;if(w.opera=="[object Opera]"){d.addEventListener("DOMContentLoaded",i,false)}else{i()}}})(window,document,"ct","7aeuz75g");
</script>
<!-- calltouch -->
