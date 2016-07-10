<?php

namespace AppBundle;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    public function upload(UploadedFile $file)
    {
		do{
			$fileName = md5(random_bytes(10)) . '.' . $file->guessExtension();
		} while(file_exists($this->targetDir . '/' . $fileName));

        $file->move($this->targetDir, $fileName);

        return $fileName;
    }
}