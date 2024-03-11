<?php

class Uploads {

    private $File;
    private $Name;
    private $Send;
    private $Width;
    private $Image;
    private $Result;
    private $Error;
    private $Folder;
    private static $BaseDir;

    function __construct($BaseDir = null) {
        self::$BaseDir = ((string) $BaseDir ? $BaseDir : '../sheep-imagens/');
        if (!file_exists(self::$BaseDir) && !is_dir(self::$BaseDir)) {
            mkdir(self::$BaseDir, 0755);
        }
    }

    public function Image(array $Image, $Name = null, $Width = null, $Folder = null) {
        $this->File = $Image;
        $this->Name = ((string) $Name ? $Name : pathinfo($Image['name'], PATHINFO_FILENAME));
        $this->Width = ((int) $Width ? $Width : 2000);
        $this->Folder = ((string) $Folder ? $Folder : 'images');

        $this->CheckFolder($this->Folder);
        $this->setFileName();
        $this->UploadImage();
    }

    public function getResult() {
        return $this->Result;
    }

    public function getError() {
        return $this->Error;
    }

    private function CheckFolder($Folder) {
        list($y, $m) = explode('/', date('Y/m'));
        $this->CreateFolder("{$Folder}");
        $this->CreateFolder("{$Folder}/{$y}");
        $this->CreateFolder("{$Folder}/{$y}/{$m}/");
        $this->Send = "{$Folder}/{$y}/{$m}/";
    }

    private function CreateFolder($Folder) {
        if (!file_exists(self::$BaseDir . $Folder) && !is_dir(self::$BaseDir . $Folder)) {
            mkdir(self::$BaseDir . $Folder, 0755);
        }
    }

    private function setFileName() {
        $FileName = $this->Name . strrchr($this->File['name'], '.');
        $counter = 1;

        while (file_exists(self::$BaseDir . $this->Send . $FileName)) {
            $FileName = $this->Name . '-' . $counter . strrchr($this->File['name'], '.');
            $counter++;
        }

        $this->Name = $FileName;
    }

    private function UploadImage() {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $fileExtension = strtolower(pathinfo($this->File['name'], PATHINFO_EXTENSION));

        if (!in_array($fileExtension, $allowedExtensions)) {
            $this->Result = false;
            $this->Error = 'Tipo de arquivo inválido. Envie imagens JPG, JPEG, PNG ou GIF!';
            return;
        }

        $this->Image = $this->createImageFromType();

        if (!$this->Image) {
            $this->Result = false;
            $this->Error = 'Falha ao criar imagem a partir do arquivo enviado.';
            return;
        }

        $this->resizeImage();
        $this->saveImage();
    }

    private function createImageFromType() {
        switch ($this->File['type']) {
            case 'image/jpg':
            case 'image/jpeg':
            case 'image/pjpeg':
                return imagecreatefromjpeg($this->File['tmp_name']);
            case 'image/png':
            case 'image/x-png':
                return imagecreatefrompng($this->File['tmp_name']);
            case 'image/gif':
                return imagecreatefromgif($this->File['tmp_name']);
            case 'image/vnd.wap.wbmp':
                return imagecreatefromwbmp($this->File['tmp_name']);
            default:
                return false;
        }
    }

    private function resizeImage() {
        $x = imagesx($this->Image);
        $y = imagesy($this->Image);
        $ImageX = ($this->Width < $x ? $this->Width : $x);
        $ImageH = ($ImageX * $y) / $x;

        $NewImage = imagecreatetruecolor($ImageX, $ImageH);
        imagealphablending($NewImage, false);
        imagesavealpha($NewImage, true);
        imagecopyresampled($NewImage, $this->Image, 0, 0, 0, 0, $ImageX, $ImageH, $x, $y);

        imagedestroy($this->Image);
        $this->Image = $NewImage;
    }

    private function saveImage() {
        $fileExtension = strtolower(pathinfo($this->File['name'], PATHINFO_EXTENSION));

        switch ($fileExtension) {
            case 'jpg':
            case 'jpeg':
                imagejpeg($this->Image, self::$BaseDir . $this->Send . $this->Name, 80);
                break;
            case 'png':
                imagepng($this->Image, self::$BaseDir . $this->Send . $this->Name, 8);
                break;
            case 'gif':
                imagegif($this->Image, self::$BaseDir . $this->Send . $this->Name);
                break;
        }

        imagedestroy($this->Image);

        if (file_exists(self::$BaseDir . $this->Send . $this->Name)) {
            $this->Result = $this->Send . $this->Name;
            $this->Error = null;
        } else {
            $this->Result = false;
            $this->Error = 'Falha ao salvar a imagem no servidor.';
        }
    }
}

?>
