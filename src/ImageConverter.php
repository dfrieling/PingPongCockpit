<?php

require_once(__DIR__ . '/Exception/ImageConverterException.php');

class ImageConverter
{
    const OUTPUT_HEIGHT = 750;
    const OUTPUT_WIDTH = 600;

    private $path;
    private $imagick;

    public function __construct($path)
    {
        $this->path = $path;
        $this->imagick = new Imagick($this->path);
    }

    public function toPng()
    {
        $this->imagick->setImageFormat('png');
        $this->imagick->writeImage($this->path);

        return $this;
    }

    public function cutToDimensions()
    {
        $imageprops = $this->imagick->getImageGeometry();
        $width = $imageprops['width'];
        $height = $imageprops['height'];

        if ($width > $height) {
            $newHeight = self::OUTPUT_HEIGHT;
            $newWidth = (self::OUTPUT_HEIGHT / $height) * $width;
        } else {
            $newWidth = self::OUTPUT_WIDTH;
            $newHeight = (self::OUTPUT_WIDTH / $width) * $height;
        }

        $this->imagick->resizeImage($newWidth, $newHeight, imagick::FILTER_LANCZOS, 0.9, true);
        $this->imagick->cropImage(self::OUTPUT_WIDTH, self::OUTPUT_HEIGHT, 0, 0);
        $this->imagick->writeImage($this->path);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }
}