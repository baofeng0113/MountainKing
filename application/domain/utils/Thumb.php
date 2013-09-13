<?php

class Domain_Util_Thumb
{
    private $height = '';
    private $width = '';
    private $thumbHeight = '';
    private $thumbWidth = '';
    private $type = '';
    private $resource = '';
    private $image = '';

    public function __construct($image, $thumbWidth = 600, $thumbHeight = 450)
    {
        $this->setImage($image);
        $this->setThumbHeight($thumbHeight);
        $this->setThumbWidth($thumbWidth);
    }

    private function setImage($image)
    {
        if (!empty($image) && is_string($image) && is_readable($image)) {
            $this->image = $image;
        } else {
            $this->image = false;
        }
    }

    private function setThumbHeight($height)
    {
        if (!empty($height) && is_numeric($height)) {
            $this->thumbHeight = abs($height);
        } else {
            $this->thumbHeight = 450;
        }
    }

    private function setThumbWidth($width)
    {
        if (!empty($width) && is_numeric($width)) {
            $this->thumbWidth = abs($width);
        } else {
            $this->thumbWidth = 600;
        }
    }

    private function setResource()
    {
        if ($this->image) {
            $imageInfo = getimagesize($this->image);

            switch ($imageInfo[2]) {
                case 1:
                    $this->type = 'gif';
                    $image = imagecreatefromgif($this->image);
                    break;
                case 2:
                    $this->type = 'jpg';
                    $image = imagecreatefromjpeg($this->image);
                    break;
                case 3:
                    $this->type = 'png';
                    $image = imagecreatefrompng($this->image);
                    break;
                default:
                    return false;
                    break;
            }

            $this->height = imagesy($image);
            $this->width = imagesx($image);

            if ($this->height < $this->thumbHeight && $this->width < $this->thumbWidth) {
                $this->resource = $image;
            } else {
                if ($this->height > $this->width)
                    $this->thumbWidth = $this->width * abs($this->thumbHeight / $this->height);
                else
                    $this->thumbHeight = $this->height * abs($this->thumbWidth / $this->width);

                $this->resource = imagecreatetruecolor($this->thumbWidth, $this->thumbHeight);
                imagecopyresampled($this->resource, $image, 0, 0, 0, 0, $this->thumbWidth,
                    $this->thumbHeight, $this->width, $this->height);
            }
            return true;
        }
        return false;
    }

    final public function display()
    {
        if ($this->setResource()) {
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Cache-Control: no-store, no-cache, must-revalidate');
            header('Cache-Control: post-check=0, pre-check=0', false);
            header('Pragma: no-cache');

            switch ($this->type) {
                case 'gif':
                    header('Content-type: image/gif');
                    imagegif($this->resource, false, 95);
                    break;
                case 'jpg':
                    header('Content-type: image/jpg');
                    imagejpeg($this->resource, false, 95);
                    break;
                case 'png':
                    header('Content-type: image/png');
                    imagepng($this->resource, false, 95);
                    break;
                default:
                    return false;
                    break;
            }
            imagedestroy($this->resource);
        }
        return false;
    }

    final public function deposit($path)
    {
        if ($this->setResource()) {
            switch ($this->type) {
                case 'gif':
                    imagegif($this->resource, $path);
                    break;
                case 'jpg':
                    imagejpeg($this->resource, $path);
                    break;
                case 'png':
                    imagepng($this->resource, $path);
                    break;
                default:
                    return false;
                    break;
            }
            imagedestroy($this->resource);
        }
        return false;
    }
}