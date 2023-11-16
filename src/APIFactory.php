<?php

namespace Webmasterskaya\Unisender\Bitrix;

use Bitrix\Main\Config\Option;
use PsrDiscovery\Entities\CandidateEntity;
use PsrDiscovery\Implementations\Psr17\RequestFactories;
use PsrDiscovery\Implementations\Psr17\StreamFactories;
use PsrDiscovery\Implementations\Psr18\Clients;
use Webmasterskaya\Unisender\Unisender;

class APIFactory
{
    protected static Unisender $instance;

    /**
     * @throws \Exception
     */
    public static function getInstance(): Unisender
    {
        if (!isset(static::$instance)) {
            static::$instance = static::createInstance();
        }

        return static::$instance;
    }

    /**
     * @throws \Exception
     */
    protected static function createInstance(): Unisender
    {
        $api_key = Option::get('webmasterskaya.unisender', 'api_key');
        if (empty($api_key)) {
            throw new \Exception('API KEY not set!');
        }

        // Регистрируем реализацию PSR-18 от Битрикса
        Clients::add(
            CandidateEntity::create(
                'bitrix/main',
                '~21',
                static function (string $class = '\Bitrix\Main\Web\HttpClient') {
                    return new $class;
                }
            )
        );

        // Устанавливаем приоритет выбора реализации PSR-17 и PSR-18
        Clients::prefer('bitrix/main');
        RequestFactories::prefer('nyholm/psr7');
        StreamFactories::prefer('nyholm/psr7');

        return new Unisender($api_key);
    }
}