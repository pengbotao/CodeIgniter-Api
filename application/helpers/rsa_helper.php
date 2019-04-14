<?php

function rsa_verify($data, $sign_str, $public_key)
{
    if(empty($sign_str)) {
        return false;
    }
    $res = openssl_get_publickey($public_key);
    if(! is_resource($res)) {
        return false;
    }
    $status = openssl_verify($data, $sign_str, $public_key);
    openssl_free_key($res);
    return $status;
}

function rsa_sign($data, $private_key)
{
    $res = openssl_get_privatekey($private_key);
    if(! is_resource($res)) {
        return false;
    }
    $sign_str = '';
    openssl_sign($data, $sign_str, $res);
    openssl_free_key($res);
    return $sign_str;
}

function rsa_create($bits = 2048)
{
    $res = openssl_pkey_new(array(
        'private_key_bits' => $bits,
        'private_key_type' => OPENSSL_KEYTYPE_RSA
    ));
    openssl_pkey_export($res, $private_key);
    $public_key = openssl_pkey_get_details($res);
    $public_key = $public_key['key'];
    return array(
        'public_key' => $public_key,
        'private_key' => $private_key,
    );
}

function rsa_pub_encrypt($data, $public_key)
{
    $res = openssl_get_publickey($public_key);
    if(! is_resource($res)) {
        return false;
    }
    if(openssl_public_encrypt($data, $crypt_text, $res)) {
        openssl_free_key($res);
        return $crypt_text;
    }
    return false;
}
function rsa_pub_decrypt($sign, $public_key)
{
    $res = openssl_get_publickey($public_key);
    if(! is_resource($res)) {
        return false;
    }
    if(openssl_public_decrypt($sign, $data, $res)) {
        openssl_free_key($res);
        return $data;
    }
    return false;
}

function rsa_pri_encrypt($data, $private_key)
{
    $res = openssl_get_privatekey($private_key);
    if(! is_resource($res)) {
        return false;
    }
    if(openssl_private_encrypt($data, $crypt_text, $res)) {
        openssl_free_key($res);
        return $crypt_text;
    }
    return false;
}

function rsa_pri_decrypt($sign, $private_key)
{
    $res = openssl_get_privatekey($private_key);
    if(! is_resource($res)) {
        return false;
    }
    if(openssl_private_decrypt($sign, $data, $res)) {
        openssl_free_key($res);
        return $data;
    }
    return false;
}

function rsa_pub_encrypt_long($data, $public_key, $bit = 2048)
{
    $len = strlen($data);
    if($len == 0) {
        return false;
    }
    $step = 0;
    $max_encrypt_len = $bit / 8 - 11;
    $rs = '';
    do {
        $rs .= rsa_pub_encrypt(substr($data, $step, $max_encrypt_len), $public_key);
        $step += $max_encrypt_len;
    } while($len > $step);
    return $rs;
}

function rsa_pub_decrypt_long($sign, $public_key, $bit = 2048)
{
    $len = strlen($sign);
    if($len == 0) {
        return false;
    }
    $step = 0;
    $split_decrypt_len = $bit / 8;
    $rs = '';
    do {
        $rs .= rsa_pub_decrypt(substr($sign, $step, $split_decrypt_len), $public_key);
        $step += $split_decrypt_len;
    } while($len > $step);
    return $rs;
}

function rsa_pri_encrypt_long($data, $private_key, $bit = 2048)
{
    $len = strlen($data);
    if($len == 0) {
        return false;
    }
    $step = 0;
    $max_encrypt_len = $bit / 8 - 11;
    $rs = '';
    do {
        $rs .= rsa_pri_encrypt(substr($data, $step, $max_encrypt_len), $private_key);
        $step += $max_encrypt_len;
    } while($len > $step);
    return $rs;
}

function rsa_pri_decrypt_long($sign, $private_key, $bit = 2048)
{
    $len = strlen($sign);
    if($len == 0) {
        return false;
    }
    $step = 0;
    $split_decrypt_len = $bit / 8;
    $rs = '';
    do {
        $rs .= rsa_pri_decrypt(substr($sign, $step, $split_decrypt_len), $private_key);
        $step += $split_decrypt_len;
    } while($len > $step);
    return $rs;
}