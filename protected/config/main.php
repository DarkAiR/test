<?php

Yii::setPathOfAlias('lib', realpath(__DIR__ . '/../../lib'));

$params = require 'params.php';

$res = array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => $params['appName'],
    'preload' => array('log'),
    'import' => array(
        'application.models.*',
        'application.models.forms.*',
        'application.components.*',
        'application.helpers.*',
        'lib.CurlHelper.*',
        'lib.ImageHelper.*',
        'ext.mAdmin.*',
    ),
    'modules' => array(
        'system2',
        'mainPage',
        'company',
        'sitemenu',
        'news',
        'contentBlocks',
        'articles',
        'carousel',
//        'banners',
        'rldd',
        'damask' => array(
            'ip' => '172.24.204.49',
        ),
    ),
    'components' => array(
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
            'loginUrl' => array('site/login'),
        ),
        'request' => array(
            'class' => 'application.components.HttpRequest',
        ),
        'urlManager' => array(
            'class' => 'application.components.UrlManager',
            'urlFormat' => 'path',
            'urlSuffix' => '/',
            'showScriptName' => false,
            'rules' => array(),
        ),
        'db' => array(
            'connectionString' => 'mysql:host=' . $params['dbHost'] . ';dbname=' . $params['dbName'],
            'emulatePrepare' => true,
            'username' => $params['dbLogin'],
            'password' => $params['dbPassword'],
            'charset' => 'utf8',
        ),
        'authManager' => array(
            'class' => 'CDbAuthManager',
            'connectionID' => 'db',
        ),
        'fs' => array(
            'class' => 'FileSystem',
            'nestedFolders' => 1,
        ),
        'session' => array(
            'class' => 'CDbHttpSession',
            'autoCreateSessionTable' => true,
            'connectionID' => 'db',
            'timeout' => 300,       // 5 minutes
            'gcProbability' => 100
        ),
        'viewRenderer' => array(
            'class' => 'lib.twig-renderer.ETwigViewRenderer',
            'twigPathAlias' => 'lib.twig.lib.Twig',
            'options' => array(
                'autoescape' => true,
            ),
            'functions' => array(
                'widget'    => array(
                    0 => 'TwigFunctions::widget',
                    1 => array('is_safe' => array('html')),
                ),
                'const'             => 'TwigFunctions::constGet',
                'static'            => 'TwigFunctions::staticCall',
                'call'              => 'TwigFunctions::call',
                'import'            => 'TwigFunctions::importResource',
                'absLink'           => 'TwigFunctions::absLink',
                'plural'            => 'TwigFunctions::plural',
                'dump'              => 'TwigFunctions::dump',
                'isDebug'           => 'TwigFunctions::isDebug',
                'link'              => 'CHtml::normalizeUrl',
                '_t'                => 'Yii::t',                                    // см. messages/config.php
            ),
            'filters' => array(
                'unset'             => 'TwigFunctions::filterUnset',
                'translit'          => 'TwigFunctions::filterTranslit',
                'externalLink'      => 'TwigFunctions::filterExternalLink',
                'fixSkype'          => 'TwigFunctions::filterFixSkype',

                'date'              => 'TwigFunctions::filterDate',                 // date from timestamp
                'formatClaimDate'   => 'TwigFunctions::filterFormatClaimDate',      // date from string for Claim
            ),
        ),
        'bootstrap' => array(
            'class' => 'lib.booster.components.Bootstrap',
            'responsiveCss' => true,
            'jqueryCss' => false,
        ),
        'errorHandler' => array(
            'errorAction' => 'site/error',
        ),
        'image' => array(
            'class' => 'ext.image.CImageComponent',
            'driver' => $params['imageDriver'],
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
                // uncomment the following to show log messages on web pages
                /*array(
                    'class'=>'CWebLogRoute',
                ),*/
            ),
        ),
        'localConfig' => array(
            'class' => 'ext.localConfig.LocalConfigExtension'
        ),
    ),
    'params' => array_merge(
        $params,
        array(
            'md5Salt' => 'ThisIsMymd5Salt(*&^%$#',
            'languages' => array(
                'ru' => 'Russian',
                'en' => 'English',
                'de' => 'Deutsch'
            ),
        )
    ),
);


$langArr = array_keys($res['params']['languages']);
$langPrefix = '<language:('. implode('|', $langArr) .')>';

$res['sourceLanguage'] = 'ru';
$res['language'] = $langArr[0];

$res['components']['urlManager']['rules'] = array(
    $langPrefix.'/'                     => 'site/index',
    '/'                                 => 'site/index',

    $langPrefix.'/contacts/'            => 'company/company/contacts',
    'contacts/'                         => 'company/company/contacts',

    $langPrefix.'/news/'                => 'news/news/blog',
    'news/'                             => 'news/news/blog',

    $langPrefix.'/rldd/'                => 'rldd/rldd/index',
    'rldd/'                             => 'rldd/rldd/index',
    $langPrefix.'/rldd/<action:\w+>/'   => 'rldd/rldd/<action>',
    'rldd/<action:\w+>/'                => 'rldd/rldd/<action>',

    $langPrefix.'/damask/'              => 'damask/damask/index',
    '/damask/'                          => 'damask/damask/index',
    $langPrefix.'/damask/<action:\w+>/' => 'damask/damask/<action>',
    '/damask/<action:\w+>/'             => 'damask/damask/<action>',

    // Admin
    'admin/'                                    => 'system2',
    'admin/<module:\w+>/'                       => '<module>',
    'admin/<module:\w+>/<controller:\w+>/'      => '<module>/admin<controller>',
    'admin/<module:\w+>/<controller:\w+>/<action:\w+>/' => '<module>/admin<controller>/<action>',
);

// Роуты для правильного преобразования ссылок в меню
$res['params']['routes'] = array(
    'contacts'  => 'company/company/contacts',
    'news'      => 'news/news/blog',
);


return $res;