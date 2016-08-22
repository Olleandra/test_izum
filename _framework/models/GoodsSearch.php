<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Goods;
use yii\db\Query;

/**
 * UserSearch represents the model behind the search form about `app\models\Goods`.
 */
class GoodsSearch extends Goods
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'sort_name', 'count'], 'safe'],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Goods::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'name' => $this->name,
        ]);

        $query->andFilterWhere(['like', 'sort_name', $this->sort_name])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
	
	public function searchGroup() {
		$query = (new Query())->select(['id1' => 'g1.id', 'id2' => 'g2.id'])->from(['g1' => 'goods'])->innerJoin('goods as g2', 'g1.name = g2.name')->where('g1.id != g2.id')->groupBy(["LEAST(g1.id, g2.id)", "GREATEST(g1.id, g2.id)"])->orderBy(['g1.id' => SORT_ASC])->all();
		if ($query == null || empty($query))
			return false;
		else {
			$text = '';
			foreach ($query As $item) {
				$text = $text .'(' .$item['id1'] .', ' .$item['id2'] .'), ';
			}
			return $text;
		}
	}
	
	public function searchNotGroup() {
		$query = (new Query())->select(["CONCAT('(', LEAST(g1.id, g2.id), ',', GREATEST(g1.id, g2.id), ')') AS row"])->distinct()->from(['g1' => 'goods'])->innerJoin('goods as g2', 'g1.name = g2.name')->where('g1.id != g2.id')->all();
		if ($query == null || empty($query))
			return false;
		else {
			$text = '';
			foreach ($query As $item) {
				$text = $text .$item['row'] .', ';
			}
			return $text;
		}
	}
	
}
