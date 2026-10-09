<?php

namespace AliAkel\Admin\Form\Field;

use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;
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
     * Convert the stored image (and thumbnails) to WebP when the upload is not already WebP.
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
     * @param string     $position Intervention place position (default center)
     *
     * @return $this
     */
    public function watermark($path, $size = 20, int $opacity = 50, string $position = 'center')
    {
        $this->requireIntervention();

        $this->watermarkOptions = [
            'path'     => $path,
            'size'     => max(1, (float) $size),
            'opacity'  => max(0, min(100, $opacity)),
            'position' => $position ?: 'center',
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

        $alreadyWebp = $this->isWebpFile($target);
        $needsWebpConversion = $this->convertToWebp && !$alreadyWebp;

        if ($this->convertToWebp) {
            $this->forceWebpStoreName();
        }

        // Already WebP and nothing else to do — keep file as-is.
        if ($this->convertToWebp && $alreadyWebp
            && empty($this->interventionCalls)
            && $this->watermarkOptions === null
            && $this->compressQuality === null
        ) {
            return $target;
        }

        $image = $this->imageManager()->read($target);

        foreach ($this->interventionCalls as $call) {
            call_user_func_array([$image, $call['method']], $call['arguments']);
        }

        if ($this->watermarkOptions) {
            $this->applyWatermark($image);
        }

        $quality = $this->compressQuality ?? 90;

        if ($needsWebpConversion || ($this->convertToWebp && $alreadyWebp)) {
            file_put_contents($target, (string) $image->toWebp($quality));
        } elseif ($this->compressQuality !== null) {
            $ext = strtolower(pathinfo($target, PATHINFO_EXTENSION));
            file_put_contents($target, (string) $this->encodeByExtension($image, $ext, $quality));
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

        $path = @Str::replaceLast('.'.$ext, '', $original);
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
            $ext = pathinfo($this->name, PATHINFO_EXTENSION);
            $path = Str::replaceLast('.'.$ext, '', $this->name);
            $path = $path.'-'.$name.'.'.$ext;

            $image = $this->imageManager()->read($file->getRealPath());

            $action = $size[2] ?? 'contain';
            // Match legacy "resize + canvas" by containing into a fixed box.
            if (in_array($action, ['resize', 'contain'], true)) {
                $image->contain($size[0], $size[1], 'ffffff');
            } elseif ($action === 'fit' || $action === 'cover') {
                $image->cover($size[0], $size[1]);
            } else {
                $image->$action($size[0], $size[1]);
            }

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
        if (!class_exists(ImageManager::class)) {
            throw new \Exception('To use image handling and manipulation, please install [intervention/image] first.');
        }
    }

    protected function imageManager(): ImageManager
    {
        if (extension_loaded('imagick')) {
            return ImageManager::imagick();
        }

        return ImageManager::gd();
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
     * Whether the given file path is already a WebP image.
     *
     * @param string $target
     *
     * @return bool
     */
    protected function isWebpFile(string $target): bool
    {
        if (Str::endsWith(Str::lower($target), '.webp')) {
            return true;
        }

        if (!is_file($target)) {
            return false;
        }

        if (function_exists('mime_content_type')) {
            $mime = @mime_content_type($target);
            if ($mime === 'image/webp') {
                return true;
            }
        }

        if (defined('IMAGETYPE_WEBP') && function_exists('exif_imagetype')) {
            return @exif_imagetype($target) === IMAGETYPE_WEBP;
        }

        return false;
    }

    /**
     * @param ImageInterface $image
     *
     * @return void
     */
    protected function applyWatermark(ImageInterface $image)
    {
        $options = $this->watermarkOptions;
        $watermarkPath = $this->resolveWatermarkPath($options['path']);

        $watermark = $this->imageManager()->read($watermarkPath);

        $targetWidth = (int) max(1, round($image->width() * ($options['size'] / 100)));
        $watermark->scale(width: $targetWidth);

        $position = $options['position'];
        $offset = in_array($position, ['center', 'centre'], true) ? 0 : 10;

        $image->place($watermark, $position, $offset, $offset, $options['opacity']);
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
     * @param ImageInterface $image
     *
     * @return string
     */
    protected function encodeImage(ImageInterface $image): string
    {
        $quality = $this->compressQuality ?? 90;

        if ($this->convertToWebp) {
            return (string) $image->toWebp($quality);
        }

        return (string) $image->encode();
    }

    /**
     * @param ImageInterface $image
     * @param string         $ext
     * @param int            $quality
     *
     * @return mixed
     */
    protected function encodeByExtension(ImageInterface $image, string $ext, int $quality)
    {
        return match ($ext) {
            'webp' => $image->toWebp($quality),
            'png'  => $image->toPng(),
            'gif'  => $image->toGif(),
            'avif' => $image->toAvif($quality),
            default => $image->toJpeg($quality),
        };
    }
}
