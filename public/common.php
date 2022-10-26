<?php

/**
 * ndCurlExec
 *
 * @param  string  $path
 * @param  string  $baseUrl
 * @return string|bool
 */
function ndCurlExec(string $path, string $baseUrl = 'https://zenryo.zenryo-ec.info/')
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, false);
    curl_setopt($ch, CURLOPT_URL, $baseUrl.$path);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    return curl_exec($ch);
}

/**
 * ndCurlExecJson
 *
 * @param  string  $path
 * @param  string  $baseUrl
 * @return mixed
 */
function ndCurlExecJson(string $path, string $baseUrl = 'https://zenryo.zenryo-ec.info/')
{
    return json_decode(ndCurlExec($path, $baseUrl), true);
}
