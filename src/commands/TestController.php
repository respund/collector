<?php
declare(strict_types=1);

namespace respund\collector\commands;

use respund\collector\exceptions\RespundException;
use respund\collector\factories\RespondentFactory;
use respund\collector\models\Response;
use respund\collector\models\Status;
use respund\collector\models\Survey;
use respund\collector\services\NodeRequestService;
use respund\collector\services\RespondentGenerationService;
use respund\collector\traits\ApplicationAwareTrait;
use Ramsey\Uuid\Uuid;
use yii\console\Controller;
use Yii;

class TestController extends Controller
{
    use ApplicationAwareTrait;

    public function actionCreateSurvey(string $surveyId) : void
    {

        // create survey entity from a json file

        $fileName = Yii::getAlias("@runtime")."/surveys/$surveyId.json";

        $json = file_get_contents($fileName);
        if(!$json) {
            echo "invalid file ";
            return;
        }

        try {
          $survey = $this->findSurvey($surveyId);
        } catch (RespundException $e) {
          $survey = null;
        }

        if($survey == null ) {
            $survey = new Survey([
                'key' => $surveyId,
                'name' => $surveyId,
                'uuid' => Uuid::uuid4()->toString(),
                'status_id' => Status::CREATED
            ]);
        }
        $survey->structure = $json;



        if(!$survey->save()) {
            $this->getApp()->error("error saving survey");
            print_r($survey->errors);
            return;
        }
        $this->getApp()->info("saved survey");


    }

    public function actionCreateRespondent(string $surveyKey, string $key) : void
    {

        $survey = $this->findSurvey($surveyKey);
        $model = (new RespondentFactory())->makeBase($survey, $key);


        if(!$model->save()) {
            $this->getApp()->error("error saving model");
            print_r($model->errors);
            return;
        }
        $this->getApp()->info("saved model");
        print_r($model->attributes);
    }

    public function actionNode() : void
    {
       $uuid = "3bcddf34-6f5a-46b6-9f8f-862e4dfa866e";
       $response = (new Response())->findByUuid($uuid);

        $service = new NodeRequestService();
        $service->checkResponse($response);
    }

    public function actionGenerate(string $surveyKey, int $amount = 1) : void
    {
        $survey = $this->findSurvey($surveyKey);
        $service = new RespondentGenerationService($survey, $amount);
        $service->run();
    }

    private function findSurvey(string $surveyKey) : Survey
    {
        $survey = (new Survey())->findByKey($surveyKey);
        if($survey === null) {
            throw new RespundException("Survey not found");
        }
        return $survey;

    }
}
