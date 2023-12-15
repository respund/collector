<?php
namespace dameter\app\controllers\api;


use yii\filters\ContentNegotiator;
use yii\rest\Controller;
use yii\filters\auth\CompositeAuth;
use yii\web\Response;

class BaseApiController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
        ];
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
                //'application/xml' => Response::FORMAT_XML,
            ],
        ];

        return $behaviors;
    }

    protected function logContext() : array
    {
        return [
            'module' =>'api',
            'controller' => static::class,
            'action' => $this->action->id,
        ];
    }


}