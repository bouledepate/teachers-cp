<?php

namespace app\helpers;

use app\components\Grade;
use app\components\GradeBuilder;

class GradesHelper
{
    protected GradeBuilder $gradeBuilder;
    public array $grades;

    public function __construct()
    {
        $this->gradeBuilder = new GradeBuilder();
        $this->grades = $this->getGrades();
    }

    protected function getGrades(): array
    {
        return [
            $this->gradeBuilder->setLetter('A')->setEquivalent(4.00)->setRange('95-100')->getGrade(),
            $this->gradeBuilder->setLetter('A-')->setEquivalent(3.67)->setRange('90-94')->getGrade(),
            $this->gradeBuilder->setLetter('B+')->setEquivalent(3.33)->setRange('85-89')->getGrade(),
            $this->gradeBuilder->setLetter('B')->setEquivalent(3.00)->setRange('80-84')->getGrade(),
            $this->gradeBuilder->setLetter('B-')->setEquivalent(2.67)->setRange('75-79')->getGrade(),
            $this->gradeBuilder->setLetter('C+')->setEquivalent(2.33)->setRange('70-74')->getGrade(),
            $this->gradeBuilder->setLetter('C')->setEquivalent(2.00)->setRange('65-69')->getGrade(),
            $this->gradeBuilder->setLetter('C-')->setEquivalent(1.67)->setRange('60-64')->getGrade(),
            $this->gradeBuilder->setLetter('D+')->setEquivalent(1.33)->setRange('55-59')->getGrade(),
            $this->gradeBuilder->setLetter('D')->setEquivalent(1.00)->setRange('50-54')->getGrade(),
            $this->gradeBuilder->setLetter('F')->setEquivalent(0)->setRange('0-49')->getGrade()
        ];
    }

    public function getGradeByMark(int $value): ?Grade
    {
        $currentGrade = null;

        /**
         * @var Grade $grade
         */
        foreach ($this->grades as $grade) {
            if ($grade->inRange($value)) {
                $currentGrade = $grade;
                break;
            }
        };

        return $currentGrade;
    }
}