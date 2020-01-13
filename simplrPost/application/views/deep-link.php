
<?php

$siteUrl =  base_url($param);
$domain =  str_replace('http://','',  $siteUrl);
$domain =  str_replace('https://','',  $domain);
$domain =  str_replace('www.','',  $domain);

?>

<html>
<head>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script>



</script>
</head>
<body>
<center style="margin-top: 40%;font-size: 30px ;"><a  id="deep-link" href="intent://<?php echo $domain;?>#Intent;scheme=http;package=com.codeapex.simplrpostprod;S.browser_fallback_url=http://103.15.67.74/pro1/simplrpost/Api/user/redirectLink?addressId=48;end">
	<img width="450px"  src="<?php echo base_url().'uploads/appicon.png'?>">
	<br/>Experienc Simplrpost On Mobile
</a></center>

<script type="text/javascript">

$( document ).ready(function() {

	rediectIntoApp();
    // window.location = "intent://103.15.67.74/pro1/simplrpost/sha#Intent;scheme=http;package=com.codeapex.simplrpostprod;S.browser_fallback_url=http://103.15.67.74/pro1/simplrpost/Api/user/redirectLink?addressId=48;end";

    // setTimeout(function () {
    //     document.location = alt;
    // }, 2500);

    // window.location = "intent://103.15.67.74/#Intent;scheme=abby;package=com.codeapex.simplrpostprod;S.browser_fallback_url=http://103.15.67.74/pro1/simplrpost/Api/user/redirectLink?addressId=48;end";

   // window.location =  "intent://details?id=com.codeapex.simplrpostprod&url=abby&referrer=Z#Intent;scheme=abby;action=android.intent.action.VIEW;package=com.codeapex.simplrpostprod;end";

});

function rediectIntoApp(){
	window.location = "intent://<?php echo $domain;?>#Intent;scheme=http;package=com.codeapex.simplrpostprod;S.browser_fallback_url=http://103.15.67.74/pro1/simplrpost/Api/user/redirectLink?addressId=48;end";
}
</script>

</body>

</html>
