<?php


namespace app\forms;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadImageForm extends Model
{
    public $image;

    public function rules()
    {
        return [
            'image' => ['image', 'required'],
            'extensions' => ['image', 'file', 'extensions' => 'jpg,jpeg,png']
        ];
    }

    public function uploadImage(UploadedFile $file, $currentImage)
    {
        $this->image = $file;

        if ($this->validate()) {

            $this->deleteCurrentImage($currentImage);

            return $this->saveImage();
        }
    }

    private function getFolder()
    {
        return \Yii::getAlias('@web') . 'images/';
    }

    private function generateFilename()
    {
        return strtolower(md5(uniqid($this->image->baseName))) . '.' . $this->image->extension;
    }

    public function deleteCurrentImage($currentImage)
    {
        if ($this->fileExists($currentImage)) {
            unlink($this->getFolder() . $currentImage);
        }
    }

    public function fileExists($currentImage)
    {
        if (!empty($currentImage) && $currentImage != null) {
            return file_exists($this->getFolder() . $currentImage);
        }
    }


    public function saveImage()
    {
        $filename = $this->generateFilename();

        $this->image->saveAs($this->getFolder() . $filename);

        return $filename;
    }

}