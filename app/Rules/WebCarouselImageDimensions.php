<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class WebCarouselImageDimensions implements ValidationRule
{
    protected $width;
    protected $height;

    public function __construct($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $imageInfo = getimagesize($value->getRealPath());

        if ($imageInfo === false) {
            $fail("El archivo debe ser una imagen.");
            return;
        }

        if ($imageInfo[0] !== $this->width || $imageInfo[1] !== $this->height) {
            $fail("La imagen debe tener las dimensiones {$this->width}x{$this->height} píxeles.");
        }
    }
}
