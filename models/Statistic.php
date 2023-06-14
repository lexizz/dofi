<?php

namespace app\models;

use app\models\query\StatisticQuery;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

/**
 * This is the model class for table "statistics".
 *
 * @property int $id
 * @property string $ip
 * @property int|null $file_id
 * @property string|null $created_at
 */
class Statistic extends ActiveRecord
{
    public int $number_downloads = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'statistics';
    }

    public static function getAttributeLabels(): array
    {
        $self = new self();
        return $self->attributeLabels();
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['ip'], 'required'],
            [['file_id'], 'integer'],
            ['file_id', 'exist', 'targetRelation' => 'file'],
            [['ip'], 'string', 'max' => 255],
            [['created_at', 'number_downloads'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'ip' => 'Ip',
            'file_id' => 'File ID',
            'created_at' => 'Created At',
            'number_downloads' => 'number of downloads',
        ];
    }

    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
                'value' => date('Y-m-d H:i:s'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     * @return StatisticQuery the active query used by this AR class.
     */
    public static function find(): StatisticQuery
    {
        return new StatisticQuery(get_called_class());
    }

    public function getFile(): ActiveQuery
    {
        return $this->hasOne(File::class, ['id' => 'file_id']);
    }
}
