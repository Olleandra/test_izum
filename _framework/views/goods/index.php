<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Goods;
use app\models\Stock;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Товары';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">

    <div class="jumbotron">
        <h1><?= Html::encode($this->title) ?><span> (Задание 5, 1)</span></h1>
    </div>

	<div class="user-index">
		<p>
			<?= Html::a('Выполнить запросы (задание 5)', ['group'], ['class' => 'btn btn-success']) ?>
		</p>
		<?php if (isset($flag)) : ?>
			<p>Результат запроса без группировки: <?php echo $returnNot; ?></p>
			<p>Результат запроса с группировкой: <?php echo $return; ?></p><br/>
			<p>Запрос к MySQL, метод с группировкой:</p><br/>
			<p><?php echo "SELECT g1.id id1, g2.id id2 <br/>
				FROM goods g1 <br/>
				INNER JOIN goods g2 ON g1.name = g2.name <br/>
				WHERE g1.id <> g2.id <br/>
				GROUP BY LEAST(g1.id, g2.id), GREATEST(g1.id, g2.id) <br/>
				ORDER BY g1.id;"; ?>
			</p><br/>
			<p>Запрос к MySQL, метод без группировки:</p><br/>
			<p><?php echo "SELECT DISTINCT CONCAT('(', LEAST(g1.id, g2.id), ',', GREATEST(g1.id, g2.id), ')') row <br/>
				FROM goods g1 <br/>
				INNER JOIN goods g2 ON g1.name = g2.name <br/>
				WHERE g1.id <> g2.id"; ?>
			</p><br/>
			<p>Запрос к MySQL, метод без группировки, - работает быстрее, так как машине приходится выполнять меньше операций для выполнения задачи.</p>
		<?php else : ?>
			<p>
				<?= Html::a('Добавить Товар (задание 1)', ['create'], ['class' => 'btn btn-success']) ?>
			</p>
			
			<?= GridView::widget([
				'dataProvider' => $dataProvider,
				'filterModel' => $searchModel,
				'columns' => [
					[
						'attribute' => 'id',
						'headerOptions' => ['width' => '70'],
					],
					[
						'attribute' => 'name',
						'value' => function($data) {
							return $data->name;
						},
						'filter' => Goods::getUnParentList(),
					],
					'sort_name',
					[
						'attribute' => 'count',
						'value' => 'stock.count',
					],
					[
						'class' => 'yii\grid\ActionColumn',
						'headerOptions' => ['width' => '80'],
					],
				],
			]); ?>
		<?php endif; ?>
	</div>
</div>
