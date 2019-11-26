<?php

$env = getenv();
var_dump($env) ;

if (ZEND_THREAD_SAFE) {
    echo 'Thead safe';
} else {
    echo 'Not Thead safe';
}


