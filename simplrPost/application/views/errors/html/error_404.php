<?php 
 $param =  end($this->uri->segment_array()); 
 $siteUrl =  base_url($param);
$domain =  str_replace('http://','',  $siteUrl);
$domain =  str_replace('https://','',  $domain);
$domain =  str_replace('www.','',  $domain);
$redirct_url = "intent://".$domain."#Intent;scheme=http;package=com.codeapex.simplrpostprod;S.browser_fallback_url=".$siteUrl.";end"; 
$ps = "https://play.google.com/store/apps/details?id=com.codeapex.simplrpostprod&hl=en";
?>
<html>
<head>
 <meta http-equiv="refresh" content="0;url=<?php echo $redirct_url;?>">
</head>
<body>
	<center style="margin-top: 40%;font-size: 30px ;"><a  id="deep-link" href="<?php echo $redirct_ur;?>">
    <img width="450px"  src="<?php echo base_url().'uploads/appicon.png'?>">
    <br/>Experienc Simplrpost On Mobile
</a></center>
<?php 
	if (headers_sent()){
      die('<script type="text/javascript">window.location=\''.$redirct_url.'\';</script‌​>');
    }else{
      ob_start();
     header("Location: ".$redirct_url);
      ob_flush();
    }  
	?>
</body>
</html>