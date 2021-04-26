<?php

function test()
{
    $file = file_get_contents('https://ya.ru/');
    try{
        file_put_contents('../htdocs/ya.html', $file);
        readfile('../htdocs/ya.html');
    } catch (exception $e) {
        echo $e->getMessage();
    }
}

test();