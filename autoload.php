<?php

use Bitrix\Main\Loader;

$vendor_dir = dirname(__FILE__) . '/vendor';

Loader::registerNamespace("Composer\\Semver\\", $vendor_dir . '/composer/semver/src');
Loader::registerNamespace("Nyholm\\Psr7\\", $vendor_dir . '/nyholm/psr7/src');
Loader::registerNamespace("Psr\\Http\\Message\\", $vendor_dir . '/psr/http-factory/src');
Loader::registerNamespace("PsrDiscovery\\", $vendor_dir . '/psr-discovery/discovery/src');
Loader::registerNamespace("PsrDiscovery\\", $vendor_dir . '/psr-discovery/http-client-implementations/src');
Loader::registerNamespace("PsrDiscovery\\", $vendor_dir . '/psr-discovery/http-factory-implementations/src');
Loader::registerNamespace("PsrDiscovery\\", $vendor_dir . '/psr-discovery/log-implementations/src');
Loader::registerNamespace("Webmasterskaya\\Unisender\\", $vendor_dir . '/webmasterskaya/unisender-php/src');

Loader::registerNamespace("Webmasterskaya\\Unisender\\Bitrix\\", dirname(__FILE__) . '/src');
