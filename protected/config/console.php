<?php
Yii::setPathOfAlias('application', dirname(__FILE__) . '/../');
Yii::setPathOfAlias('yupe', dirname(__FILE__) . '/../modules/yupe/');
Yii::setPathOfAlias('vendor', dirname(__FILE__) . '/../../vendor/');

    return array(
    // У вас этот путь может отличаться. Можно подсмотреть в config/main.php.
    'basePath'          => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name'              => 'Cron',
    'preload'           => array('log'),
    'commandMap'        => array(

    ),
    'import' => array(
        'application.commands.*',
        'application.components.*',
        'application.models.*',
        'application.modules.queue.models.*',
        'application.modules.yupe.extensions.tagcache.*',
    ),
    // Перенаправляем журнал для cron-а в отдельные файлы
    'components' => array(
         // компонент для отправки почты
        'mail' => array(
            'class' => 'application.modules.yupe.components.YMail',
        ),
        'migrator'=>array(
            'class'=>'yupe\components\Migrator',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'logFile' => 'cron.log',
                    'levels' => 'error, warning, info',
                ),
                array(
                    'class' => 'CFileLogRoute',
                    'logFile' => 'cron_trace.log',
                    'levels' => 'trace',
                ),
            ),
        ),

        'cache' => array(
            'class' => 'CDummyCache',
            'behaviors' => array(
                'clear' => array(
                    'class' => 'TaggingCacheBehavior',
                ),
            ),
        ),

           // параметры подключения к базе данных, подробнее http://www.yiiframework.ru/doc/guide/ru/database.overview
        'db' => require(dirname(__FILE__) . '/db.php'),
    ),
);