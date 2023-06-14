<?php

namespace app\models\query;

use app\models\Statistic;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the ActiveQuery class for [[\app\models\Statistic]].
 *
 * @see Statistic
 */
class StatisticQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return Statistic[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Statistic|ActiveRecord|null
     */
    public function one($db = null): Statistic|ActiveRecord|null
    {
        return parent::one($db);
    }

    public function byId(int $id, string $alias = 'statistics'): self
    {
        return $this->andWhere([$alias . '.id' => $id]);
    }

    public function byIP(string $ip, string $alias = 'statistics'): self
    {
        return $this->andWhere([$alias . '.ip' => $ip]);
    }
}
