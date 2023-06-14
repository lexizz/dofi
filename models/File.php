<?php

namespace app\models;

use app\models\query\FileQuery;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

/**
 * This is the model class for table "files".
 *
 * @property int $id
 * @property string $name
 * @property string|null $extension
 * @property string|null $path
 * @property string|null $mime
 * @property int $size in bytes
 * @property string|null $mtime time of last modification content of file (Unix timestamp)
 * @property string|null $ctime time of last inode change: permission, owner, groups (Unix timestamp)
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class File extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'files';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'size'], 'required'],
            [['size'], 'integer'],
            [['name', 'path', 'mime'], 'string', 'max' => 255],
            [['extension'], 'string', 'max' => 10],
            [['name', 'extension'], 'unique', 'targetAttribute' => ['name', 'extension']],
            [['mtime', 'ctime', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'extension' => 'Extension',
            'path' => 'Path',
            'mime' => 'Mime',
            'size' => 'Size',
            'mtime' => 'Mtime',
            'ctime' => 'Ctime',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => date('Y-m-d H:i:s'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     * @return FileQuery the active query used by this AR class.
     */
    public static function find(): FileQuery
    {
        return new FileQuery(get_called_class());
    }

    public function getStatistics(): ActiveQuery
    {
        return $this->hasMany(Statistic::class, ['file_id' => 'id']);
    }
}
