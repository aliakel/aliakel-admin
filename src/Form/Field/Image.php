<?php

namespace AliAkel\Admin\Form\Field;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class Image extends File
{
    use ImageField;

    /**
     * {@inheritdoc}
     */
    protected $view = 'admin::form.file';

    /**
     *  Validation rules.
     *
     * @var string
     */
    protected $rules = 'image';

    /**
     * @param array|UploadedFile|null $image
     *
     * @return string|null
     */
    public function prepare($image)
    {
        if ($this->picker) {
            return parent::prepare($image);
        }

        $hasValidUpload = $image instanceof UploadedFile && $image->isValid();

        // Explicit remove (ajax) with no replacement file.
        if (request()->has(static::FILE_DELETE_FLAG) && !$hasValidUpload) {
            $this->destroy();

            return null;
        }

        // Submit without a new image — keep the existing column value.
        if (!$hasValidUpload) {
            return $this->original;
        }

        $this->name = $this->getStoreName($image);

        $this->callInterventionMethods($image->getRealPath());

        $path = $this->uploadAndDeleteOriginal($image);

        if ($path) {
            $this->uploadAndDeleteOriginalThumbnail($image);
        }

        return $path;
    }

    /**
     * force file type to image.
     *
     * @param $file
     *
     * @return array|bool|int[]|string[]
     */
    public function guessPreviewType($file)
    {
        $extra = parent::guessPreviewType($file);
        $extra['type'] = 'image';

        return $extra;
    }
}
