<?php


namespace app\helpers;


use app\enums\MonthEnum;
use app\models\Estimate;
use app\models\Group;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

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

    public static function setCollapse($disciplineId, $userId, $asList = false, $reverse = false)
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

        if ($asList) {
            return Html::ul($estimates->all(), ['item' => function ($item, $index) {
                return Html::tag(
                    'li',
                    self::declOfNumber($item->value, array('балл', 'балла', 'баллов')) . ' | ' . $item->created_at
                );
            }]);
        }

        $contentData = static::groupByMonth((new ActiveDataProvider(['query' => $estimates]))->getModels());
        static::calculateAverageValue($contentData);

        $table = "<table class='table table-sm table-striped'><tr><th>Баллы</th><th>Дата</th><th>Выставил</th><th>Удаление</th></tr>";
        foreach ($contentData as $month => $marks) {

            $table .= "<tr><th colspan='3'>$month</th>";

            $removeMarksByMonthLink = Url::to(['estimates/remove-marks-by-month', 'id' => $marks[0]->user_discipline_id, 'month' => \Yii::$app->formatter->asDatetime($marks[0]->created_at, 'M')]);
            $table .= "<th><a href='$removeMarksByMonthLink' title='Удалить баллы за $month'><i class='bi bi-x-circle'> Удалить за $month</i></a></th></tr>";

            foreach ($marks as $mark) {
                if ($mark instanceof Estimate) {
                    $removeMarkLink = Url::to(['estimates/remove-mark', 'id' => $mark->id]);
                    $table .= "<tr><td>{$mark->value}</td><td>{$mark->created_at}</td>
                                <td>{$mark->author->profile->getFullname()}</td>
                                <td><a href='$removeMarkLink' title='Удалить баллы'><i class='bi bi-x-circle'></i> Удалить</a></td></tr>";
                } else {
                    $table .= "<tr><th class='table-success' colspan='4'>РО: $mark</th></tr>";
                }
            }
        }

        $total = 0;
        $count = 0;

        foreach ($contentData as $month) {
            $total += $month['average'];
            $count++;
        }

        $totalAverage = round($total / $count, 2);
        $table .= "<th class='table-danger' colspan='3'>РД: $totalAverage</th>";

        $removeMarksLink = Url::to(['estimates/remove-marks', 'id' => array_shift($contentData)[0]->user_discipline_id]);
        $table .= "<th><a href='$removeMarksLink' title='Удалить все баллы'><i class='bi bi-x-circle'> Удалить все</i></a></th></tr></table>";

        return $table;
    }

    public static function groupByMonth($models): array
    {
        $result = [];

        foreach ($models as $model) {
            $month = MonthEnum::getMonth(\Yii::$app->formatter->asDatetime($model->created_at, 'M'));

            if (!isset($result[$month])) {
                $result[$month] = [];
            }

            $result[$month][] = $model;
        }

        return $result;
    }

    public static function calculateAverageValue(array &$data)
    {
        foreach ($data as $month => $marks) {
            $total = 0;
            $count = 0;

            foreach ($marks as $markInfo) {
                $total += $markInfo->value;
                $count++;
            }

            $data[$month]['average'] = round($total / $count, 2);
        }
    }

    private static function declOfNumber($num, $titles)
    {
        $cases = array(2, 0, 1, 1, 1, 2);
        return $num . " " . $titles[($num % 100 > 4 && $num % 100 < 20) ? 2 : $cases[min($num % 10, 5)]];
    }

    public static function getMarksTableData(int $groupId, int $disciplineId, $month = null)
    {
        $group = Group::findOne(['id' => $groupId])->getUsers()->all();

        $result = [];
        $includedMonths = [];

        /**
         * @var User $student
         */
        foreach ($group as $student) {
            $result[$student->profile->getFullname()] = [];

            $userDiscipline = User::getUserDisciplineRelationId($student->id, $disciplineId);
            $marks = Estimate::find()->where(['user_discipline_id' => $userDiscipline->id])->orderBy('created_at');
            $data = static::groupByMonth((new ActiveDataProvider(['query' => $marks]))->getModels());

            foreach ($data as $currentMonth => $monthMarks) {

                if ($month && $currentMonth != $month) {
                    continue;
                }

                $includedMonths[] = $currentMonth;

                /**
                 * @var Estimate $mark
                 */
                array_map(function ($mark) use (&$result, $currentMonth, $student) {
                    $result[$student->profile->getFullname()][$currentMonth][\Yii::$app->formatter->asDate($mark->created_at, 'd')] = $mark->value;
                }, $monthMarks);
            }
        }


        return [
            'includedMonths' => array_unique($includedMonths),
            'result' => $result
        ];
    }
}