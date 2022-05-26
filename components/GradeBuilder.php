<?php

namespace app\components;

class GradeBuilder implements GradeBuilderInterface
{
    private Grade $grade;

    public function __construct()
    {
        $this->reset();
    }

    protected function reset()
    {
        $this->grade = new Grade();
    }

    public function setLetter(string $letter): GradeBuilder
    {
        $this->grade->letter = $letter;

        return $this;
    }

    public function setEquivalent(float $equivalent): GradeBuilder
    {
        $this->grade->equivalent = $equivalent;

        return $this;
    }

    public function setRange(string $range): GradeBuilder
    {
        $this->grade->range = $range;

        return $this;
    }

    public function getGrade(): Grade
    {
        $grade = $this->grade;
        $this->reset();

        return $grade;
    }
}