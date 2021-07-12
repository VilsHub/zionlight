<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit01291a4741376af5afe27525bf3e6abb
{
    public static $files = array (
        '0a7a0ac4e525c92b8478eea73fae76c6' => __DIR__ . '/../../../..' . '/app/lib/functions/system/navigator.php',
        'e77308555442dd6c07b0a83a7d91d7c8' => __DIR__ . '/../../../..' . '/app/lib/functions/system/output.php',
    );

    public static $prefixLengthsPsr4 = array (
        'v' => 
        array (
            'vilshub\\validator\\' => 18,
            'vilshub\\router\\' => 15,
            'vilshub\\http\\' => 13,
            'vilshub\\helpers\\' => 16,
            'vilshub\\dbant\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'vilshub\\validator\\' => 
        array (
            0 => __DIR__ . '/..' . '/vilshub/validator/src',
        ),
        'vilshub\\router\\' => 
        array (
            0 => __DIR__ . '/..' . '/vilshub/router/src',
        ),
        'vilshub\\http\\' => 
        array (
            0 => __DIR__ . '/..' . '/vilshub/http/src',
        ),
        'vilshub\\helpers\\' => 
        array (
            0 => __DIR__ . '/..' . '/vilshub/helpers/src',
        ),
        'vilshub\\dbant\\' => 
        array (
            0 => __DIR__ . '/..' . '/vilshub/dbant/src',
        ),
    );

    public static $classMap = array (
        'CSRF' => __DIR__ . '/../../../..' . '/app/lib/classes/system/helpers/CSRF.php',
        'Campaign' => __DIR__ . '/../../../..' . '/app/lib/classes/application/controllers/Campaign.php',
        'CampaignModel' => __DIR__ . '/../../../..' . '/app/lib/classes/application/models/campaignModel.php',
        'CheckIP' => __DIR__ . '/../../../..' . '/app/lib/classes/application/middleWares/checkIP.php',
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'ContentLoader' => __DIR__ . '/../../../..' . '/app/lib/classes/application/controllers/ContentLoader.php',
        'Controller' => __DIR__ . '/../../../..' . '/app/lib/classes/system/core/controller.php',
        'Cookie' => __DIR__ . '/../../../..' . '/app/lib/classes/system/helpers/Cookie.php',
        'DataParser' => __DIR__ . '/../../../..' . '/app/lib/classes/system/helpers/DataParser.php',
        'ErrorHandler' => __DIR__ . '/../../../..' . '/app/lib/classes/system/helpers/ErrorHandler.php',
        'Loader' => __DIR__ . '/../../../..' . '/app/lib/classes/system/helpers/Loader.php',
        'Model' => __DIR__ . '/../../../..' . '/app/lib/classes/system/core/model.php',
        'Route' => __DIR__ . '/../../../..' . '/app/lib/classes/system/helpers/Route.php',
        'ServiceRequest' => __DIR__ . '/../../../..' . '/app/lib/classes/application/controllers/ServiceRequest.php',
        'ServiceRequestModel' => __DIR__ . '/../../../..' . '/app/lib/classes/application/models/serviceRequestModel.php',
        'Session' => __DIR__ . '/../../../..' . '/app/lib/classes/system/helpers/Session.php',
        'SessionReg' => __DIR__ . '/../../../..' . '/app/lib/classes/application/controllers/SessionReg.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit01291a4741376af5afe27525bf3e6abb::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit01291a4741376af5afe27525bf3e6abb::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit01291a4741376af5afe27525bf3e6abb::$classMap;

        }, null, ClassLoader::class);
    }
}
