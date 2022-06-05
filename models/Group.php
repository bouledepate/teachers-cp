<?php


namespace app\models;


use app\enums\MonthEnum;
use app\helpers\GradesHelper;
use app\helpers\CertificationHelper;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $name
 * @property string $module
 * @property string $speciality
 * @property array $users
 * @property-read string $role
 */
class Group extends ActiveRecord
{
    public $user = null;

    public function rules()
    {
        return [
            'required' => [['name', 'module', 'speciality'], 'required'],
            'integer' => ['id', 'integer'],
            'length' => [['name', 'module', 'speciality'], 'string', 'max' => 255],
            'unique' => ['name', 'unique'],
            'trim' => [['name', 'module', 'speciality'], 'trim'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID группы',
            'name' => 'Название группы',
            'module' => 'Модуль',
            'speciality' => 'Специальность'
        ];
    }

    public static function create($params)
    {
        $group = new Group();
        $group->name = $params->name;
        $group->module = $params->module;
        $group->speciality = $params->speciality;
        $group->save();
    }

    public function getDisciplines()
    {
        return $this->hasMany(Discipline::class, ['id' => 'discipline_id'])
            ->viaTable('group_discipline', ['group_id' => 'id']);
    }

    public function getUsers()
    {
        return $this->hasMany(User::class, ['group_id' => 'id']);
    }

    private static function addDisciplinesToStudent($user, $groupId)
    {
        $group = Group::findOne(['id' => $groupId]);
        $disciplines = $group->getDisciplines()->all();
        foreach($disciplines as $discipline){
            if(!$user->hasRelationWithDiscipline($discipline->id)){
                $user->link('disciplines', $discipline);
            }
        }

    }

    private static function removeDisciplinesFromStudent($user){
        $group = Group::findOne(['id' => $user->group_id]);
        $disciplines = $group->getDisciplines()->all();
        foreach($disciplines as $discipline){
            $user->unlink('disciplines', $discipline);
        }
    }

    public static function addStudents($array, $groupId)
    {
        foreach ($array as $key => $value) {
            $user = User::findOne(['id' => $value]);
            self::addDisciplinesToStudent($user, $groupId);
            $user->setGroup($groupId);
        }
    }

    public static function removeStudent($id)
    {
        $user = User::findOne(['id' => $id]);
        self::removeDisciplinesFromStudent($user);
        $user->setGroup(null);
    }

    public function addDisciplines($array)
    {
        foreach ($array as $key => $value) {
            $discipline = Discipline::findOne(['id' => $value]);
            $this->link('disciplines', $discipline);

            // Добавляем студентам группы дисциплину ID.
            foreach ($this->users as $user) {
                $user->link('disciplines', $discipline);
            }
        }
    }

    public function removeDiscipline($id)
    {
        $discipline = Discipline::findOne(['id' => $id]);
        $this->unlink('disciplines', $discipline);

        // Убираем дисциплину у студентов.
        foreach ($this->users as $user) {
            $user->unlink('disciplines', $discipline);
        }
    }

    public function hasDiscipline($dId)
    {
        return Group::find()->joinWith('disciplines')->where([
            'group_discipline.group_id' => $this->id,
            'group_discipline.discipline_id' => $dId
        ])->one();
    }

    public function getStudentsCertification(Certification $certification)
    {
        $result = [];
        $gradesHelper = new GradesHelper();

        $result = array_merge($result, [
            'group' => $this->name,
            'discipline' => $certification->discipline->name,
            'teacher' => \Yii::$app->user->identity->profile->getFullname(),
            'module' => $this->module,
            'speciality' => $this->speciality,
            'students' => []
        ]);

        foreach ($certification->results as $data) {
            $userData = [
                'name' => $data->user->profile->getFullname(),
                'examMark' => $data->mark,
                'periodMark' => $this->getTotalEstimateResultByPeriod($certification->period, $data),
            ];

            if ($certification->type === Certification::TYPE_EXAM) {
                $userData = array_merge($userData, ['ticket' => $data->ticket]);
            }

            $userData = array_merge($userData, [
                'periodGrade' => $gradesHelper->getGradeByMark($userData['periodMark']),
                'examGrade' => $gradesHelper->getGradeByMark($data->mark),
            ]);

            $totalMark = $this->calculateTotalMark($userData['examMark'], $userData['periodMark']);

            $userData = array_merge($userData, [
                'totalMark' => $totalMark,
                'totalGrade' => $gradesHelper->getGradeByMark($totalMark)
            ]);

            $result['students'][] = $userData;
        }

        return $result;
    }

    protected function getTotalEstimateResultByPeriod(int $period, UserCertification $data)
    {
        $total = 0;
        $months = CertificationHelper::getMonthsByKeys($period);

        foreach ($months as $month) {
            $monthTotal = 0;
            $userDisciplineRelation = User::getUserDisciplineRelationId($data->user_id, $data->certification->discipline_id);
            $marks = Estimate::getByMonth($userDisciplineRelation->id, array_search($month, MonthEnum::getMonths()));

            foreach ($marks as $mark) {
                $monthTotal += $mark->value;
            }

            if (count($marks) > 0) {
                $total += $monthTotal / count($marks);
            }
        }

        return intval($total / count($months));
    }

    protected function calculateTotalMark(int $examMark, int $periodMark): int
    {
        return intval((0.6 * $periodMark) + (0.4 * $examMark));
    }

    public function deleteGroup()
    {
        \Yii::$app->db->transaction(function ($database) {
            \Yii::$app->db->createCommand("DELETE FROM group_discipline WHERE group_id = {$this->id}");
            User::updateAll([
                'group_id' => null
            ], ['group_id' => $this->id]);
        });

        return $this->delete();
    }
}