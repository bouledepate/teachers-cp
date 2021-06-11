<?php


namespace app\helpers;


use app\models\Estimate;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;

class EstimateHelper
{
    public static function setButton($id)
    {
        return Html::button('Просмотреть', [
            'class' => 'btn btn-sm btn-primary',
            'data-toggle' => 'collapse',
            'data-target' => '#collapseEstimates-' . $id,
            'aria-expanded' => 'true',
            'aria-controls' => 'collapseEstimates-' . $id
        ]);
    }

    public static function setCollapse($disciplineId, $userId, $asList=false, $reverse=false)
    {
        $beginWrapper = Html::beginTag('div', [
            'class' => 'collapse',
            'id' => $reverse ? 'collapseEstimates-' . $userId : 'collapseEstimates-' . $disciplineId,
        ]);
        $content = self::setCollapseContent($userId, $disciplineId, $asList);
        $endWrapper = Html::endTag('div');

        return $beginWrapper . $content . $endWrapper;
    }

    public static function setCollapseContent($userId, $disciplineId, $asList)
    {
        $userDiscipline = User::getUserDisciplineRelationId($userId, $disciplineId);
        $estimates = Estimate::find()->where(['user_discipline_id' => $userDiscipline->id])->orderBy('created_at');

        if (!$estimates->all()) {
            return Html::tag('span', 'Баллы ещё не выставлены', ['class' => 'text-muted']);
        }

        if($asList){
            return Html::ul($estimates->all(), ['item' => function($item, $index){
                return Html::tag(
                    'li',
                    self::declOfNumber($item->value, array('балл', 'балла', 'баллов')) . ' | ' . $item->created_at
                );
            }]);
        }

        return GridView::widget([
            'dataProvider' => new ActiveDataProvider([
                'query' => $estimates,
            ]),
            'tableOptions' => [
                'class' => 'table table-striped table-bordered table-sm'
            ],
            'columns' => [
                [
                    'label' => 'Баллы',
                    'attribute' => 'value'
                ],
                [
                    'label' => 'Дата',
                    'attribute' => 'created_at'
                ],
                [
                    'label' => 'Выставил',
                    'format' => 'raw',
                    'value' => function($data) {
                        return Html::a($data->author->username, ['profile/index', 'username' => $data->author->username]);
                    }
                ]
            ]
        ]);
    }

    public static function totalEstimateDisplay($userId, $disciplineId)
    {
        $userDiscipline = User::getUserDisciplineRelationId($userId, $disciplineId);
        $estimates = Estimate::findAll(['user_discipline_id' => $userDiscipline['id']]);
        $total = 0;

        foreach ($estimates as $estimate) {
            $total += $estimate->value;
        }

        return Html::tag(
            'span',
            self::declOfNumber($total, array('балл', 'балла', 'баллов')),
            ['class' => self::styleByValue($total)]
        );
    }

    private static function declOfNumber($num, $titles)
    {
        $cases = array(2, 0, 1, 1, 1, 2);
        return $num . " " . $titles[($num % 100 > 4 && $num % 100 < 20) ? 2 : $cases[min($num % 10, 5)]];
    }

    private static function styleByValue($value)
    {
        if ($value >= 75) {
            return 'badge badge-success';
        } elseif ($value >= 50 && $value < 75) {
            return 'badge badge-warning';
        } else {
            return 'badge badge-danger';
        }
    }
}