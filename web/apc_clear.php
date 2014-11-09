<?php

$allowDevIps = array('127.0.0.1', 'fe80::1', '::1');

$config = __DIR__ . '/../app/config/allow_dev_ips.php';

if (file_exists($config)) {
    $allowDevIps = include $config;
}

if (isset($_SERVER['HTTP_CLIENT_IP'])
    || !in_array(@$_SERVER['REMOTE_ADDR'], $allowDevIps)
) {
    header('HTTP/1.0 403 Forbidden');
    exit('Your IP: ' . $_SERVER['REMOTE_ADDR']);
}

apc_clear_cache();
apc_clear_cache('user');
apc_clear_cache('opcode');
apc_clear_cache('sf2');

echo json_encode(array('success' => true));
