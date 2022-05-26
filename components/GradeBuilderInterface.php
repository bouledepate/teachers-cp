<?php

namespace app\components;

interface GradeBuilderInterface
{
    public function setLetter(string $letter): GradeBuilder;

    public function setEquivalent(float $equivalent): GradeBuilder;

    public function setRange(string $range): GradeBuilder;
}