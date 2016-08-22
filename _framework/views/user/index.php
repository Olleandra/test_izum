<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\User;
use app\models\Profile;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">

    <div class="jumbotron">
        <h1><?= Html::encode($this->title) ?><span></span></h1>
    </div>

	<div class="user-index">
		<p>
			<?= Html::a('Добавить пользователя', ['create'], ['class' => 'btn btn-success']) ?>
		</p>
		<?= GridView::widget([
			'dataProvider' => $dataProvider,
			'filterModel' => $searchModel,
			'columns' => [
				[
					'attribute' => 'id',
					'headerOptions' => ['width' => '70'],
				],
				'login',
				[
					'attribute' => 'name',
					'value' => function($model) {
						return $model->name;
					},
					'filter' => User::getUnParentList(),
				],
				'email:email',
				[
					'attribute' => 'role',
					'value' => function ($data) {
						return app\models\User::getRoleName($data->role);
					},
					'headerOptions' => ['width' => '80'],
					'filter' => User::getRoleList(),
				],
				[
					'attribute' => 'status',
					'format' => 'raw',
					'value' => function ($data) {
						return '<span class="glyphicon glyphicon-'.(($data->status==app\models\User::STATUS_ACTIVE)?'ok':'remove').'" aria-hidden="true"></span>';
					},
					'headerOptions' => ['width' => '40'],
				],
				[
					'attribute' => 'last_visit',
					'value' => function ($data) {
						return \Yii::$app->formatter->asDatetime($data->last_visit);
					},
					'headerOptions' => ['width' => '140'],
				],
				[
					'class' => 'yii\grid\ActionColumn',
					'headerOptions' => ['width' => '80'],
				],
			],
		]); ?>
	</div>
</div>
