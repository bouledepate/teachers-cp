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
    <?= $form->field($model, 'period')->hiddenInput()->label(false) ?>
    <div class="row pb-4 mb-3 border-bottom">
        <div class="col col-3">
            <h4>Тип аттестации</h4>
            <?= $form->field($model, 'type')->radioList(Certification::getCertificationTypes(), ['separator' => '<br>'])->label(false) ?>
        </div>
        <div class="col col-3" id="exam-type-selector" style="display: none">
            <h4>Формат экзамена</h4>
            <?= $form->field($model, 'subtype')->radioList(Certification::getExamTypes(), ['separator' => '<br>'])->label(false) ?>
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
                        <?= $form->field($model, 'date')->textInput(['type' => 'datetime-local'])->label(false) ?>
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
<td>{$student->profile->getFullname()} <input type=\"hidden\" name=\"UserCertification[users][]\" value=\"{$student->id}\"></td>
<td><input type=\"number\" name=\"UserCertification[marks][]\" min=\"0\" max=\"100\" class=\"form-control-sm\"></td>
<td><input type=\"number\" name=\"UserCertification[tickets][]\" min=\"0\" max=\"100\" class=\"form-control-sm\" disabled></td>
</tr>";
                    } ?>
                    <input type="hidden" name="UserCertification[count]" value="<?= count($group->users) ?>">
                </table>
            </div>
        </div>
    </div>
    <input type="submit" value="Сохранить" class="btn btn-success">
    <?php \yii\widgets\ActiveForm::end() ?>
</div>

<script type="application/javascript">
    let certificationTypeSelectors = document.getElementsByName('Certification[type]')
    let monthCheckboxes = document.getElementsByName('period_list[]')
    let ticketInputs = document.getElementsByName('UserCertification[tickets][]')

    function changeTicketInputsState(type) {
        if (type === "0") {
            for (let id = 0; id < ticketInputs.length; id++) {
                ticketInputs[id].required = true
                ticketInputs[id].disabled = false
                ticketInputs[id].value = ''
            }
        } else {
            for (let id = 0; id < ticketInputs.length; id++) {
                ticketInputs[id].required = false
                ticketInputs[id].disabled = true
                ticketInputs[id].value = ''
            }
        }
    }

    function resetForm() {
        let formElements = document.getElementById('w0').elements

        for (let id = 0; id < formElements.length; id++) {
            formElements[id].value = ''
        }
    }

    for (let id = 0; id < certificationTypeSelectors.length; id++) {
        certificationTypeSelectors[id].addEventListener('click', function (event) {
            let examTypeSelector = document.getElementById('exam-type-selector')

            if (event.target.value === "0") {
                examTypeSelector.style.display = 'block'
            } else {
                examTypeSelector.style.display = 'none'
            }

            changeTicketInputsState(event.target.value)
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