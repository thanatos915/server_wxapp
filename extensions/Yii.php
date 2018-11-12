
<?php
/**
 * Created by PhpStorm.
 * User: thanatos
 * Date: 2018/4/23
 * Time: 下午3:48
 */


class Yii extends \yii\BaseYii
{
    /**
     * @var BaseApplication|WebApplication|ConsoleApplication the application instance
     */
    public static $app;
}

/**
 * Class BaseApplication
 * Used for properties that are identical for both WebApplication and ConsoleApplication
 * read-only.
 */
abstract class BaseApplication extends yii\base\Application
{
}

/**
 * Class WebApplication
 * Include only Web application related components here
 */
abstract class WebApplication extends  yii\web\Application
{
}


/**
 * Class ConsoleApplication
 * @property \yii\db\Connection $dbMigrateDdy
 * @property \yii\db\Connection $dbMigrateTbz
 * Include only Console application related components here
 */
abstract class ConsoleApplication extends yii\console\Application
{
}