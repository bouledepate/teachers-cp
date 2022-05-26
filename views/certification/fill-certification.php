<?php

/**
 * @var Certification $model
 * @var \app\models\Discipline $discipline
 * @var \app\models\Group $group
 */

use app\models\Certification;
use yii\helpers\Html;

$this->title = 'Аттестация группы ' . $group->name;
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?= Html::encode($this->title) ?></h1><br>
</div>

<div class="container-fluid">
    <?php $form = \yii\widgets\ActiveForm::begin() ?>
    <?= $form->field($model, 'group_id')->hiddenInput(['value' => $group->id])->label(false) ?>
    <?= $form->field($model, 'discipline_id')->hiddenInput(['value' => $discipline->id])->label(false) ?>
    <?= $form->field($model, 'type')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'subtype')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'period')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'date')->hiddenInput()->label(false) ?>
    <?php \yii\widgets\ActiveForm::end() ?>
    <div class="row pb-4 mb-3 border-bottom">
        <div class="col col-3">
            <h4>Тип аттестации</h4>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="certification-type-select"
                       value="<?= Certification::TYPE_EXAM ?>" id="type-exam">
                <label class="form-check-label" for="type-exam">
                    Экзамен
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="certification-type-select"
                       value="<?= Certification::TYPE_MIDTERM ?>" id="type-midterm">
                <label class="form-check-label" for="type-midterm">
                    Зачёт
                </label>
            </div>
        </div>
        <div class="col col-3" id="exam-type-selector" style="display: none">
            <h4>Формат экзамена</h4>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="exam-type-selector"
                       value="<?= Certification::TYPE_ORAL_EXAM ?>" id="type-oral">
                <label class="form-check-label" for="type-oral">
                    Устный
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="exam-type-selector"
                       value="<?= Certification::TYPE_WRITTEN_EXAM ?>" id="type-written">
                <label class="form-check-label" for="type-written">
                    Письменный
                </label>
            </div>
        </div>
    </div>
    <div class="row mt-2" id="additional-data">
        <div class="col-4">
            <div class="row">
                <div class="col col-6">
                    <div class="form-group">
                        <p class="font-weight-bold">Выбор периода аттестации:</p>
                        <?= Html::checkboxList('period_list', null, \app\helpers\CertificationHelper::getMonths(), ['separator' => '<br>']) ?>
                    </div>
                </div>
                <div class="col col-6">
                    <div class="form-group">
                        <p class="font-weight-bold">Дата проведения экзамена:</p>
                        <input type="datetime-local" id="date" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-8">
            <div class="col">
                <p class="font-weight-bold">Список студентов:</p>
                <table class="table table-sm table-striped">
                    <tr>
                        <th>Имя студента</th>
                        <th>Баллы</th>
                        <th>Номер билета</th>
                    </tr>
                    <?php
                    foreach ($group->users as $student) {
                        echo "<tr>
<td>{$student->profile->getFullname()} <input type=\"hidden\" name=\"user[]\" value=\"{$student->id}\"></td>
<td><input type=\"number\" name=\"mark[]\" min=\"0\" max=\"100\" class=\"form-control-sm\" required></td>
<td><input type=\"number\" name=\"ticket[]\" min=\"0\" max=\"100\" class=\"form-control-sm\" required></td>
</tr>";
                    } ?>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="application/javascript">
    let certificationTypeSelectors = document.getElementsByName('certification-type-select')
    let examTypeSelectors = document.getElementsByName('exam-type-selector')
    let monthCheckboxes = document.getElementsByName('period_list[]')

    function resetForm() {
        let formElements = document.getElementById('w0').elements

        for (let id = 0; id < formElements.length; id++) {
            formElements[id].value = ''
        }
    }

    document.getElementById('date').addEventListener('change', function (event) {
        document.getElementById('certification-date').value = event.target.value
    })

    for (let id = 0; id < certificationTypeSelectors.length; id++) {
        certificationTypeSelectors[id].addEventListener('change', function (event) {
            resetForm()
            document.getElementById('certification-type').value = event.target.value;

            let examTypeSelector = document.getElementById('exam-type-selector')

            if (event.target.value === "0") {
                examTypeSelector.style.display = 'block'
            } else {
                examTypeSelector.style.display = 'none'
            }
        })
    }

    for (let id = 0; id < examTypeSelectors.length; id++) {
        examTypeSelectors[id].addEventListener('change', function (event) {
            document.getElementById('certification-subtype').value = event.target.value;
        })
    }

    for (let id = 0; id < monthCheckboxes.length; id++) {
        monthCheckboxes[id].addEventListener('change', function (event) {
            if (event.target.checked) {
                document.getElementById('certification-period').value |= event.target.value;
            } else {
                document.getElementById('certification-period').value ^= event.target.value;
            }
        })
    }
</script>