<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Главная страница';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">

    <div class="jumbotron">
        <h1><?= Html::encode($this->title) ?><span> (Тестовое задание)</span></h1>
    </div>

	<div class="site-index">
		<p>
			<?= Html::a('Пользователи', ['user/index'], ['class' => 'btn btn-success']) ?>
			<?= Html::a('Профили', ['profile/index'], ['class' => 'btn btn-success']) ?>
			<?= Html::a('Товары', ['goods/index'], ['class' => 'btn btn-success']) ?>
		</p>
	</div>
</div>
