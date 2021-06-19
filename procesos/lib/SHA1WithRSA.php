<?php
date_default_timezone_set("AMERICA/SANTIAGO"); 
function hextobin($hexstr)
{
$n = strlen($hexstr);
$sbin="";
$i=0;
while($i<$n)
{
$a =substr($hexstr,$i,2);
$c = pack("H*",$a);
if ($i==0){$sbin=$c;}
else {$sbin.=$c;}
$i+=2;
}
return $sbin;
}
 
function buildSign($toSign, $privkey, $publickey) {
 
$signature = null;
$priv_key = $privkey;
$pub_key = openssl_get_publickey($publickey);
//$toSign = sha1($toSign);
$pkeyid = openssl_get_privatekey($priv_key);

openssl_sign($toSign, $signature, $priv_key, OPENSSL_ALGO_SHA1);


$ok = openssl_verify($toSign, $signature, $pub_key);

if ($ok == 1) {
 //   echo "VALIDA";
} elseif ($ok == 0) {
 //   echo "NO VALIDA";
} else {
    echo "error: ".openssl_error_string();
}

openssl_free_key($pkeyid);

//$hex = bin2hex($signature);
$base64 = base64_encode($signature);
return $base64;

}
 
function verifySign($sign, $toSign, $publickey) {
 
$signdata = hextobin($sign);
 
$ret = openssl_verify($toSign, $signdata, $publickey);

return $ret;
}
 
function verifySign_der($sign, $toSign) {
 
$signdata = hextobin($sign);
 
$der = file_get_contents('pubkey.der');
$pem = "-----BEGIN PUBLIC KEY-----\n";
$str = base64_encode($der);
$pem .= wordwrap($str, 64, "\n", true)."\n";
$pem .= "-----END PUBLIC KEY-----\n";
 
$ret = openssl_verify($toSign, $signdata, $pem);
return $ret;
}
 
 
?>
