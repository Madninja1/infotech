<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "author_subscription".
 *
 * @property int $id
 * @property int $author_id
 * @property string $phone
 * @property int $created_at
 *
 * @property Author $author
 */
class AuthorSubscription extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%author_subscription}}';
    }

    public function rules(): array
    {
        return [
            [['author_id', 'phone'], 'required'],
            ['author_id', 'integer'],
            [
                'phone',
                'match',
                'pattern' => '/^\+?[0-9]{10,15}$/',
                'message' => 'Введите телефон в формате +79991234567'
            ],
            [
                ['author_id', 'phone'],
                'unique',
                'targetAttribute' => ['author_id', 'phone'],
                'message'         => 'Вы уже подписаны на этого автора.'
            ],
        ];
    }
}
