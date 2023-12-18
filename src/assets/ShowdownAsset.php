<?php
namespace respund\collector\assets;

use yii\web\AssetBundle;

class ShowdownAsset extends AssetBundle
{
    public $sourcePath = '@npm/showdown/dist';

    public $js = [
        'showdown.min.js',
    ];

}