<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\User;
use app\models\Profile;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Профили';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">

    <div class="jumbotron">
        <h1><?= Html::encode($this->title) ?><span> (Задание 2)</span></h1>
    </div>

	<div class="user-index">
		<p>
			<?= Html::a('Добавить профиль', ['create'], ['class' => 'btn btn-success']) ?>
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
					'attribute' => 'user_id',
					'value' => function ($data) {
						return $data->user->name;
					},
					'filter' => User::getParentList(),
				],
				[
					'attribute' => 'name',
					'value' => function($data) {
						return $data->name;
					},
					/*'filter' => Profile::getUnParentList(),*/
				],
				[
					'class' => 'yii\grid\ActionColumn',
					'headerOptions' => ['width' => '80'],
				],
			],
		]); ?>
	</div>
</div>
