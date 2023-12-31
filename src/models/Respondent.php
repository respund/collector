<?php
declare(strict_types=1);

namespace respund\collector\models;
use respund\collector\traits\KeyRecordTrait;
use respund\collector\traits\UuidRecordTrait;
use respund\collector\traits\WithStatusRecordTrait;
use yii\db\ActiveQuery;

/**
 * @property int $respondent_id
 * @property int $survey_id
 * @property int $language_id
 * @property string $uuid
 * @property string $key
 * @property ?string $params json
 *
 * @property Survey $survey
 * @property ?Response $response
 */
class Respondent extends TimedActiveRecord implements KeyedModelInterface, UuidModelInterface
{
    use UuidRecordTrait;
    use KeyRecordTrait;
    use WithStatusRecordTrait;

    public static function tableName() : string
    {
        return 'respondent';
    }

    public function rules() : array
    {
        return array_merge(parent::rules(),[
            [['survey_id', 'status_id', 'uuid', 'language_id', 'key'], 'required'],
            [['uuid','key'], 'string', 'max' => 45],
            [['uuid'], 'unique'],
            [['params'], 'string'],
            [['language_id', 'status_id', 'survey_id'], 'integer'],
        ]);
    }



    public function getSurvey(): ActiveQuery
    {
        return $this->hasOne(Survey::class, ['survey_id' => 'survey_id']);
    }

    public function getResponse(): ActiveQuery
    {
        return $this->hasOne(Response::class, ['respondent_id' => 'respondent_id']);
    }

}