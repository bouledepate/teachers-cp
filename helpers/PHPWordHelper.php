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

        $rendererName = Settings::PDF_RENDERER_MPDF;
        $rendererLibraryPath = __DIR__ . '\..\vendor\mpdf\mpdf';

        Settings::setPdfRenderer($rendererName, $rendererLibraryPath);

        $this->setFont();
    }

    public function generateNewReport(array $data, Certification $certification)
    {
        $template = $this->importTemplate();

        $this->fillBaseInformation($data, $template, $certification);
        $this->fillStudentMarksTable($data, $template, $certification);

        return $this->saveReport($template, $certification);
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

        return $name . '.pdf';
    }

    protected function importTemplate()
    {
        return $this->builder->loadTemplate(__DIR__ . '/../web/templates/' . $this->templateName);
    }

    protected function saveReport(TemplateProcessor $template, Certification $certification)
    {
        $template->saveAs('temp.docx');
        $buffer = IOFactory::load('temp.docx');
        unlink('temp.docx');

        return $buffer->save($this->setFilename($certification), 'PDF', true);
    }
}