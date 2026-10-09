<?php

namespace AliAkel\Admin\Form\Field;

use Illuminate\Support\Str;
use Intervention\Image\Constraint;
use Intervention\Image\Facades\Image as InterventionImage;
use Intervention\Image\ImageManagerStatic;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait ImageField
{
    /**
     * Intervention calls.
     *
     * @var array
     */
    protected $interventionCalls = [];

    /**
     * Thumbnail settings.
     *
     * @var array
     */
    protected $thumbnails = [];

    /**
     * Encode quality percentage (1–100). Null keeps Intervention default.
     *
     * @var int|null
     */
    protected $compressQuality = null;

    /**
     * Convert uploaded image to WebP.
     *
     * @var bool
     */
    protected $convertToWebp = false;

    /**
     * Watermark options: path, size (% of image width), opacity (0–100), position.
     *
     * @var array|null
     */
    protected $watermarkOptions = null;

    /**
     * Default directory for file to upload.
     *
     * @return mixed
     */
    public function defaultDirectory()
    {
        return config('admin.upload.directory.image');
    }

    /**
     * Compress / encode quality (1–100%).
     *
     * @param int $percentage
     *
     * @return $this
     */
    public function compress(int $percentage)
    {
        $this->requireIntervention();

        $this->compressQuality = max(1, min(100, $percentage));

        return $this;
    }

    /**
     * Convert the stored image (and thumbnails) to WebP.
     *
     * @param bool $enabled
     *
     * @return $this
     */
    public function convertToWebp(bool $enabled = true)
    {
        $this->requireIntervention();

        $this->convertToWebp = $enabled;

        return $this;
    }

    /**
     * Overlay a watermark on the uploaded image.
     *
     * @param string     $path     Absolute path, or relative to public/ / storage/app/public/
     * @param int|float  $size     Watermark width as % of image width (default 20)
     * @param int        $opacity  0–100 (default 50)
     * @param string     $position Intervention insert position (default bottom-right)
     *
     * @return $this
     */
    public function watermark($path, $size = 20, int $opacity = 50, string $position = 'bottom-right')
    {
        $this->requireIntervention();

        $this->watermarkOptions = [
            'path'     => $path,
            'size'     => max(1, (float) $size),
            'opacity'  => max(0, min(100, $opacity)),
            'position' => $position ?: 'bottom-right',
        ];

        return $this;
    }

    /**
     * Execute Intervention calls + compress / webp / watermark.
     *
     * @param string $target
     *
     * @return mixed
     */
    public function callInterventionMethods($target)
    {
        if (!$this->shouldProcessImage()) {
            return $target;
        }

        $this->requireIntervention();

        if ($this->convertToWebp) {
            $this->forceWebpStoreName();
        }

        $image = ImageManagerStatic::make($target);

        foreach ($this->interventionCalls as $call) {
            call_user_func_array([$image, $call['method']], $call['arguments']);
        }

        if ($this->watermarkOptions) {
            $this->applyWatermark($image);
        }

        $format = $this->outputFormat($target);
        $quality = $this->compressQuality;

        if ($format !== null) {
            $image->save($target, $quality, $format);
        } elseif ($quality !== null) {
            $image->save($target, $quality);
        } else {
            $image->save($target);
        }

        return $target;
    }

    /**
     * Call intervention methods.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function __call($method, $arguments)
    {
        if (static::hasMacro($method)) {
            return $this;
        }

        $this->requireIntervention();

        $this->interventionCalls[] = [
            'method'    => $method,
            'arguments' => $arguments,
        ];

        return $this;
    }

    /**
     * Render a image form field.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        $this->options(['allowedFileTypes' => ['image'], 'msgPlaceholder' => trans('admin.choose_image')]);

        return parent::render();
    }

    /**
     * @param string|array $name
     * @param int          $width
     * @param int          $height
     *
     * @return $this
     */
    public function thumbnail($name, int $width = null, int $height = null)
    {
        if (func_num_args() == 1 && is_array($name)) {
            foreach ($name as $key => $size) {
                if (count($size) >= 2) {
                    $this->thumbnails[$key] = $size;
                }
            }
        } elseif (func_num_args() == 3) {
            $this->thumbnails[$name] = [$width, $height];
        }

        return $this;
    }

    /**
     * Destroy original thumbnail files.
     *
     * @return void.
     */
    public function destroyThumbnail()
    {
        if ($this->retainable) {
            return;
        }

        foreach ($this->thumbnails as $name => $_) {
            /*  Refactoring actual remove lofic to another method destroyThumbnailFile()
            to make deleting thumbnails work with multiple as well as
            single image upload. */

            if (is_array($this->original)) {
                if (empty($this->original)) {
                    continue;
                }

                foreach ($this->original as $original) {
                    $this->destroyThumbnailFile($original, $name);
                }
            } else {
                $this->destroyThumbnailFile($this->original, $name);
            }
        }
    }

    /**
     * Remove thumbnail file from disk.
     *
     * @return void.
     */
    public function destroyThumbnailFile($original, $name)
    {
        $ext = @pathinfo($original, PATHINFO_EXTENSION);

        // We remove extension from file name so we can append thumbnail type
        $path = @Str::replaceLast('.'.$ext, '', $original);

        // We merge original name + thumbnail name + extension
        $path = $path.'-'.$name.'.'.$ext;

        if ($this->storage->exists($path)) {
            $this->storage->delete($path);
        }
    }

    /**
     * Upload file and delete original thumbnail files.
     *
     * @param UploadedFile $file
     *
     * @return $this
     */
    protected function uploadAndDeleteOriginalThumbnail(UploadedFile $file)
    {
        foreach ($this->thumbnails as $name => $size) {
            // We need to get extension type ( .jpeg , .png ...)
            $ext = pathinfo($this->name, PATHINFO_EXTENSION);

            // We remove extension from file name so we can append thumbnail type
            $path = Str::replaceLast('.'.$ext, '', $this->name);

            // We merge original name + thumbnail name + extension
            $path = $path.'-'.$name.'.'.$ext;

            /** @var \Intervention\Image\Image $image */
            $image = InterventionImage::make($file);

            $action = $size[2] ?? 'resize';
            // Resize image with aspect ratio
            $image->$action($size[0], $size[1], function (Constraint $constraint) {
                $constraint->aspectRatio();
            })->resizeCanvas($size[0], $size[1], 'center', false, '#ffffff');

            $encoded = $this->encodeImage($image);

            if (!is_null($this->storagePermission)) {
                $this->storage->put("{$this->getDirectory()}/{$path}", $encoded, $this->storagePermission);
            } else {
                $this->storage->put("{$this->getDirectory()}/{$path}", $encoded);
            }
        }

        $this->destroyThumbnail();

        return $this;
    }

    /**
     * @throws \Exception
     */
    protected function requireIntervention()
    {
        if (!class_exists(ImageManagerStatic::class)) {
            throw new \Exception('To use image handling and manipulation, please install [intervention/image] first.');
        }
    }

    protected function shouldProcessImage(): bool
    {
        return !empty($this->interventionCalls)
            || $this->watermarkOptions !== null
            || $this->compressQuality !== null
            || $this->convertToWebp;
    }

    /**
     * Force store name extension to .webp.
     */
    protected function forceWebpStoreName()
    {
        if (empty($this->name) || !is_string($this->name)) {
            return;
        }

        if (Str::endsWith(Str::lower($this->name), '.webp')) {
            return;
        }

        if (preg_match('/\.[^.]+$/', $this->name)) {
            $this->name = preg_replace('/\.[^.]+$/', '.webp', $this->name);
        } else {
            $this->name .= '.webp';
        }
    }

    /**
     * @param \Intervention\Image\Image $image
     *
     * @return void
     */
    protected function applyWatermark($image)
    {
        $options = $this->watermarkOptions;
        $watermarkPath = $this->resolveWatermarkPath($options['path']);

        $watermark = ImageManagerStatic::make($watermarkPath);

        $targetWidth = (int) max(1, round($image->width() * ($options['size'] / 100)));
        $watermark->resize($targetWidth, null, function (Constraint $constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        if ($options['opacity'] < 100) {
            $watermark->opacity($options['opacity']);
        }

        $image->insert($watermark, $options['position'], 10, 10);
    }

    /**
     * @param string $path
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    protected function resolveWatermarkPath(string $path): string
    {
        if (is_file($path)) {
            return $path;
        }

        $candidates = [
            public_path($path),
            storage_path('app/public/'.$path),
            storage_path('app/'.$path),
            base_path($path),
        ];

        foreach ($candidates as $candidate) {
            if (is_file($candidate)) {
                return $candidate;
            }
        }

        throw new \InvalidArgumentException("Watermark image not found: {$path}");
    }

    /**
     * @param string $target
     *
     * @return string|null
     */
    protected function outputFormat(string $target): ?string
    {
        if ($this->convertToWebp) {
            return 'webp';
        }

        if (!function_exists('exif_imagetype') || !@is_file($target)) {
            return null;
        }

        $type = @exif_imagetype($target);
        if ($type === false) {
            return null;
        }

        $ext = image_type_to_extension($type, false);

        return $ext ?: null;
    }

    /**
     * @param \Intervention\Image\Image $image
     *
     * @return \Intervention\Image\Image
     */
    protected function encodeImage($image)
    {
        $quality = $this->compressQuality ?? 90;

        if ($this->convertToWebp) {
            return $image->encode('webp', $quality);
        }

        if ($this->compressQuality !== null) {
            return $image->encode(null, $quality);
        }

        return $image->encode();
    }
}
