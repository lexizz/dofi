<?php

namespace app\models\query;

use app\models\File;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the ActiveQuery class for [[\app\models\File]].
 *
 * @see File
 */
class FileQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return File[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return File|array|ActiveRecord|null
     */
    public function one($db = null): File|ActiveRecord|null
    {
        return parent::one($db);
    }

    public function byId(int $id, string $alias = 'files'): self
    {
        return $this->andWhere([$alias . '.id' => $id]);
    }

    public function byIds(array $ids, string $alias = 'files'): self
    {
        return $this->andWhere([$alias . '.id' => $ids]);
    }

    public function byName(string $name, string $alias = 'files'): self
    {
        return $this->andWhere([$alias . '.name' => $name]);
    }
}
