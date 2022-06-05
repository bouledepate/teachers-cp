<?php

namespace app\helpers;

use app\models\Certification;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\TemplateProcessor;

class PHPWordHelper
{
    protected PhpWord $builder;
    protected string $templateName = 'report.docx';
    protected array $baseStyle = ['underline' => Font::UNDERLINE_SINGLE, 'size' => 14];

    public function __construct()
    {
        $this->builder = new PhpWord();
        $this->setFont();

        Settings::setOutputEscapingEnabled(true);
        Settings::setDefaultFontSize(14);
        Settings::setDefaultFontName('Times New Roman');
    }

    public function generateNewReport(array $data, Certification $certification)
    {
        $template = $this->importTemplate();

        $this->fillBaseInformation($data, $template, $certification);
        $this->fillStudentMarksTable($data, $template, $certification);

        $this->saveReport($template, $certification);
    }

    protected function fillBaseInformation(array $data, TemplateProcessor $template, Certification $certification)
    {
        $university = new TextRun();
        $university->addText('Саранский высший гуманитарно-технический колледж имени Абая Кунанбаева', $this->baseStyle);
        $template->setComplexValue('university', $university);

        $exam = new TextRun();
        $midterm = new TextRun();

        if ($certification->type === Certification::TYPE_EXAM) {
            $exam->addText('ЭКЗАМЕНАЦИОННАЯ', $this->baseStyle);
            $midterm->addText('ЗАЧЕТНАЯ', ['size' => 14]);
        } else {
            $midterm->addText('ЗАЧЕТНАЯ', $this->baseStyle);
            $exam->addText('ЭКЗАМЕНАЦИОННАЯ', ['size' => 14]);
        }

        $template->setComplexValue('exam', $exam);
        $template->setComplexValue('midterm', $midterm);

        $group = new TextRun();
        $group->addText($data['group'], $this->baseStyle);
        $template->setComplexValue('group', $group);

        $teacher = new TextRun();
        $teacher->addText($data['teacher'], $this->baseStyle);
        $template->setComplexValue('teacher', $teacher);

        $module = new TextRun();
        $module->addText($data['module'], $this->baseStyle);
        $template->setComplexValue('module', $module);

        $speciality = new TextRun();
        $speciality->addText($data['speciality'], $this->baseStyle);
        $template->setComplexValue('speciality', $speciality);

        return $template;
    }

    protected function fillStudentMarksTable(array $data, TemplateProcessor $template, Certification $certification)
    {
        $rows = [];

        foreach ($data['students'] as $key => $value) {
            $rowData = [
                'user' => $key + 1,
                'name' => $value['name'],
                'lp_mark' => $value['periodGrade']->letter,
                'np_mark' => $value['periodGrade']->equivalent
            ];

            if ($certification->type === Certification::TYPE_EXAM) {
                $rowData = array_merge($rowData, [
                    'ticket' => $value['ticket']
                ]);

                if ($certification->subtype === Certification::TYPE_WRITTEN_EXAM) {
                    $rowData = array_merge($rowData, [
                        'lwe_mark' => $value['examGrade']->letter,
                        'nwe_mark' => $value['examGrade']->equivalent,
                        'loe_mark' => null,
                        'noe_mark' => null
                    ]);
                } else {
                    $rowData = array_merge($rowData, [
                        'loe_mark' => $value['examGrade']->letter,
                        'noe_mark' => $value['examGrade']->equivalent,
                        'lwe_mark' => null,
                        'nwe_mark' => null,
                    ]);
                }
            } else {
                $rowData = array_merge($rowData, [
                    'ticket' => null,
                    'loe_mark' => null,
                    'noe_mark' => null,
                    'lwe_mark' => null,
                    'nwe_mark' => null,
                ]);
            }

            $rowData = array_merge($rowData, [
                'lt_mark' => $value['totalGrade']->letter,
                'nt_mark' => $value['totalGrade']->equivalent
            ]);

            $rows[] = $rowData;
        }

        $template->cloneRowAndSetValues('user', $rows);

        return $template;
    }

    protected function setFont(): void
    {
        $this->builder->setDefaultFontName('Times New Roman');
        $this->builder->setDefaultFontSize(12);
    }

    protected function setFilename(Certification $certification): string
    {
        $name = 'report';

        if ($certification->type === Certification::TYPE_EXAM) {
            if ($certification->subtype === Certification::TYPE_ORAL_EXAM) {
                $name .= '-oexam-' . time();
            } else {
                $name .= '-wexam-' . time();
            }
        } else {
            $name .= '-midterm-' . time();
        }

        return $name . '.docx';
    }

    protected function importTemplate()
    {
        return $this->builder->loadTemplate(__DIR__ . '/../web/templates/' . $this->templateName);
    }

    protected function saveReport(TemplateProcessor $template, Certification $certification)
    {
        $filename = $this->setFilename($certification);

        $template->saveAs($filename);

        if (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($filename));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filename));
        readfile($filename);

        unlink($filename);
    }
}