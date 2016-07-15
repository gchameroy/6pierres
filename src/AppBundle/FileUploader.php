<?php

namespace AppBundle;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gregwar\Image\Image;

class FileUploader
{
    private $targetDir;
    public function __construct($targetDir, $thumbsDir)
    {
        $this->targetDir = $targetDir;
        $this->thumbsDir = $thumbsDir;
    }

    public function uploadFile(UploadedFile $file)
    {
		do{
			$fileName = md5(random_bytes(10)) . '.jpg';
		} while(file_exists($this->targetDir . '/' . $fileName));
		
		$file->move($this->targetDir, $fileName);
		$this->cropFile($fileName);
		
		$thumbName = $this->makeThumb($fileName);

		$response = new \stdClass;
		$response->fileName = $fileName;
		$response->thumbName = $thumbName;
        return $response;
    }
	
	private function cropFile($fileName)
	{
		Image::open($this->targetDir . '/' . $fileName)
			->cropResize(2000, 2000)
			->save($this->targetDir . '/' . $fileName, 'jpg', 30);
	}
	
	private function makeThumb($fileName)
	{
		do{
			$thumbName = md5(random_bytes(10)) . '.jpg';
		} while(file_exists($this->thumbsDir . '/' . $thumbName));
		
		Image::open($this->targetDir . '/' . $fileName)
			->zoomCrop(350, 263)
			->save($this->thumbsDir . '/' . $thumbName, 'jpg', 100);
		
		return $thumbName;
	}
}