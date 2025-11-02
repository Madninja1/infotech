<?php

namespace app\models;

use app\components\SmsPilotClient;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\web\UploadedFile;

class Book extends ActiveRecord
{
    /** @var int[]|null множественный выбор авторов в формах */
    public ?array $authorIds = null;

    public $coverFile = null;

    public static function tableName(): string
    {
        return '{{%book}}';
    }

    public function behaviors(): array
    {
        return [TimestampBehavior::class];
    }


    public function rules(): array
    {
        return [
            [['title', 'year'], 'required'],
            ['title', 'string', 'max' => 255],
            ['year', 'integer', 'min' => 1400, 'max' => (int)date('Y') + 1],
            ['isbn', 'string', 'max' => 32],
            ['isbn', 'match', 'pattern' => '/^[0-9\-xX]+$/'],
            ['cover_url', 'url'],
            [
                'coverFile',
                'file',
                'extensions'  => ['jpg', 'jpeg', 'png', 'webp'],
                'maxSize'     => 2 * 1024 * 1024,
                'skipOnEmpty' => true
            ],
            ['description', 'string'],
            ['authorIds', 'required', 'message' => 'Выберите хотя бы одного автора.'],
            ['authorIds', 'each', 'rule' => ['integer']],
        ];
    }

    public function attributeLabels(): array
    {
        return ['authorIds' => 'Authors'];
    }

    public function getAuthors()
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])
            ->viaTable('{{%book_author}}', ['book_id' => 'id']);
    }

    public function afterFind()
    {
        $this->authorIds = (new Query())
            ->select('author_id')->from('{{%book_author}}')
            ->where(['book_id' => $this->id])->column();

        parent::afterFind();
    }

    public function beforeValidate()
    {
        $this->coverFile = UploadedFile::getInstance($this, 'coverFile') ?: $this->coverFile;

        return parent::beforeValidate();
    }

    public function afterSave($insert, $changedAttributes)
    {
        $this->saveAuthorsPivot();

        if ($insert) {
            $this->notifySubscribers();
        }

        if ($this->coverFile) {
            $content = file_get_contents($this->coverFile->tempName);
            $safeName = 'book_' . $this->id . '.' . $this->coverFile->extension;
            $url = Yii::$app->storage->save('covers', $safeName, $content);

            static::updateAll(['cover_url' => $url], ['id' => $this->id]);
            $this->cover_url = $url;
        }

        parent::afterSave($insert, $changedAttributes);
    }

    private function saveAuthorsPivot(): void
    {
        if ($this->authorIds === null) {
            return;
        }

        $db = static::getDb();
        $tx = $db->beginTransaction();

        try {
            $db->createCommand()->delete('{{%book_author}}', ['book_id' => $this->id])->execute();
            if ($this->authorIds) {
                $rows = array_map(fn($aid) => [$this->id, (int)$aid], $this->authorIds);
                $db->createCommand()->batchInsert('{{%book_author}}', ['book_id', 'author_id'], $rows)->execute();
            }
            $tx->commit();
        } catch (\Throwable $e) {
            $tx->rollBack();
            throw $e;
        }
    }

    /** Рассылка SMS подписчикам авторов (эмулятор smspilot) */
    private function notifySubscribers(): void
    {
        /** @var SmsPilotClient $sms */
        $sms = Yii::$app->sms;

        $authorIds = (new Query())
            ->select('author_id')->from('{{%book_author}}')
            ->where(['book_id' => $this->id])->column();

        if (!$authorIds) {
            return;
        }

        $phones = (new Query())
            ->select('phone')
            ->distinct()
            ->from('{{%author_subscription}}')
            ->where(['author_id' => $authorIds])->column();

        if (!$phones) {
            return;
        }

        $msg = sprintf('Новая книга: "%s" (%d)', $this->title, $this->year);
        foreach ($phones as $phone) {
            $sms->send($phone, $msg);
        }
    }
}
