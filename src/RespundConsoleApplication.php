<?php
declare(strict_types=1);

namespace respund\collector;

use respund\collector\traits\ApplicationTrait;
use yii\console\Application;

class RespundConsoleApplication extends Application implements ApplicationInterface
{
    use ApplicationTrait;

}