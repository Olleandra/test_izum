<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->login.' ('.$model->name.')';
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите БЕЗВОЗВРАТНО удалить пользователя и все данные с ним связанные?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'login',
            'name',
            'email:email',
            [
                'attribute' => 'role',
                'value' => $model::getRoleName($model->role),
            ],
            [
                'attribute' => 'status',
                'value' => $model::getStatusName($model->status),
            ],
            [
                'attribute' => 'last_visit',
                'value' => \Yii::$app->formatter->asDatetime($model->last_visit),
            ],
            [
                'attribute' => 'created_at',
                'value' => \Yii::$app->formatter->asDatetime($model->created_at),
            ],
            [
                'attribute' => 'updated_at',
                'value' => \Yii::$app->formatter->asDatetime($model->updated_at),
            ],
        ],
    ]) ?>

</div>
