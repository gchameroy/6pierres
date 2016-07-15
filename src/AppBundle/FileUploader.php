<?php

namespace AppBundle;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Intervention\Image\ImageManagerStatic as Image;

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
			$fileName = md5(random_bytes(10)) . '.' . $file->guessExtension();
		} while(file_exists($this->targetDir . '/' . $fileName));
		
		do{
			$thumbName = md5(random_bytes(10)) . '.' . $file->guessExtension();
		} while(file_exists($this->thumbsDir . '/' . $thumbName));
		
		$file->move($this->targetDir, $fileName);
		$this->cropFile($fileName);
		$this->makeThumb($fileName, $thumbName);

		$response = new \stdClass;
		$response->fileName = $fileName;
		$response->thumbName = $thumbName;
        return $response;
    }
	
	private function cropFile($fileName)
	{
		$image = Image::make($this->targetDir . '/' . $fileName)
			->widen(2000)
			->heighten(2000);
		$orientation = $image->exif('Orientation');
		if($orientation == 6){
			$image->rotate(-90);
		}
		$image->save($this->targetDir . '/' . $fileName);
	}
	
	private function makeThumb($fileName, $thumbName)
	{
		Image::make($this->targetDir . '/' . $fileName)
			->fit(350, 263)
			->save($this->thumbsDir . '/' . $thumbName, 100);
	}
}