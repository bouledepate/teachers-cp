<?php

namespace app\models\search;


use app\models\User;
use yii\data\ActiveDataProvider;

class UserSearch extends User
{
    public $firstName;
    public $secondName;
    public $lastName;

    public function rules()
    {
        return [
            ['id', 'integer'],
            [['username', 'email', 'status', 'firstName', 'secondName', 'lastName'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return parent::scenarios();
    }

    public function search($params)
    {
        $query = User::find()->joinWith('profile');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10
            ]
        ]);

        // загружаем данные формы поиска и производим валидацию
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // изменяем запрос добавляя в его фильтрацию
        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'status', $this->status]);
        $query->andFilterWhere(['like', 'profile.first_name', $this->firstName])
            ->andFilterWhere(['like', 'profile.second_name', $this->secondName])
            ->andFilterWhere(['like', 'profile.last_name', $this->lastName]);

        return $dataProvider;
    }
}