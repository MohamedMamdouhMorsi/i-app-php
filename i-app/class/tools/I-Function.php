<?php

function test(){
return "Hello World Test";
}
function creatAUTH($x) {
    $salt = "app";
    $salt1 = "be";
    $salt2 = "gh";
    $salt3 = "vt";
    $salt4 = "sd";

    $mdfcode1 = hash('md5', $x);
    $sha1code1 = hash('sha1', $mdfcode1);
    $cryptcode1 = hash_hmac('sha256', $sha1code1, $salt);

    $mdfcode2 = hash('md5', $cryptcode1);
    $sha1code2 = hash('sha1', $mdfcode2);
    $cryptcode2 = hash_hmac('sha256', $sha1code2, $salt1);

    $mdfcode3 = hash('md5', $cryptcode2);
    $sha1code3 = hash('sha1', $mdfcode3);
    $cryptcode3 = hash_hmac('sha256', $sha1code3, $salt2);

    $mdfcode4 = hash('md5', $cryptcode3);
    $sha1code4 = hash('sha1', $mdfcode4);
    $cryptcode4 = hash_hmac('sha256', $sha1code4, $salt3);

    $mdfcode5 = hash('md5', $cryptcode4);
    $sha1code5 = hash('sha1', $mdfcode5);
    $cryptcode5 = hash_hmac('sha256', $sha1code5, $salt4);

    $mdfcode6 = hash('md5', $cryptcode5);
    $sha1code6 = hash('sha1', $mdfcode6);
    $cryptcode6 = hash_hmac('sha256', $sha1code6, $salt);
    $mdfcode6 = hash('md5', $cryptcode6);

    $supercode = "I-app-" . $mdfcode6;
    return $supercode;
}

?>