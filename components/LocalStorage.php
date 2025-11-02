<?php
namespace app\components;

use yii\base\Component;
use yii\helpers\FileHelper;

class LocalStorage extends Component
{
    /** каталог в webroot, куда складываем файлы */
    public string $basePath = '@webroot/uploads';

    /** публичный префикс URL */
    public string $baseUrl  = '@web/uploads';

    public function save(string $subdir, string $fileName, string $content): string
    {
        $path = \Yii::getAlias($this->basePath) . '/' . trim($subdir, '/');
        FileHelper::createDirectory($path, 0775, true);

        $full = $path . '/' . $fileName;
        file_put_contents($full, $content);

        $url = \Yii::getAlias($this->baseUrl) . '/' . trim($subdir, '/') . '/' . $fileName;
        return $url;
    }
}
