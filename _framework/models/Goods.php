<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use app\models\Goods;
use app\models\Stock;

/**
 * This is the model class for table "goods".
 *
 * @property string $id
 * @property string $name
 * @property string $sort_name
 */
class Goods extends \yii\db\ActiveRecord
{
	public $count;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'sort_name'], 'required'],
            [['name'], 'string', 'max' => 64],
            [['sort_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Вид товара',
            'sort_name' => 'Сорт',
            'count' => 'Количество на складе',
        ];
    }
	
	public function afterSave($insert, $changedAttributes) 
	{
		parent::afterSave($insert, $changedAttributes);
		$stock = Stock::find()->where(['goods_id' => $this->id])->one();
		if($stock == null || empty($stock)){
			/* если мы создаем новый товар, тогда нам необходимо создать 
			для него запись в таблице Склад с ссылкой на родительскую таблицу*/
			$stock = new Stock;
			$stock->goods_id = $this->id;
			$stock->count = $this->count;
			$stock->save();
		} else {
			/* иначе неободимо обновить данные в таблице Склад */
			$stock->count = $this->count;
			$stock->save();
		}
    }
	
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStock()
    {
        return $this->hasOne(Stock::className(), ['goods_id' => 'id']);
    }
	
    /**
     * @inheritdoc
     * @return GoodsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GoodsQuery(get_called_class());
    }
	
	public static function getUnParentList()
    {
		$parent_all = Goods::find()->all();
		$parent = ArrayHelper::map($parent_all, 'name', 'name');
        return $parent;
    }
}
