<?php

namespace AliAkel\Admin\Form\Field;

use Closure;
use AliAkel\Admin\Form\Field;

class Subcard extends Field
{
    /**
     * @var string
     */
    protected $view = 'admin::form.subcard';

    /**
     * @var string
     */
    protected $title = '';

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @var string|Closure|null
     */
    protected $content;

    /**
     * @var Field[]
     */
    protected $fields = [];

    /**
     * @var Closure|null
     */
    protected $builder;

    /**
     * @param string $title
     * @param array  $arguments
     */
    public function __construct($title = '', $arguments = [])
    {
        $this->title = (string) $title;

        $content = $arguments[0] ?? null;

        if ($content instanceof Closure) {
            $this->builder = $content;
        } else {
            $this->content = $content;
        }
    }

    /**
     * Optional description under the title.
     *
     * @param string $description
     *
     * @return $this
     */
    public function description($description)
    {
        $this->description = (string) $description;

        return $this;
    }

    /**
     * Nested fields collected while building this card.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return $this->fields;
    }

    /**
     * @param Field $field
     *
     * @return $this
     */
    public function pushField(Field $field)
    {
        $field->setWidth(12, 0);
        $field->disableHorizontal();

        $this->fields[] = $field;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function fill($data)
    {
        foreach ($this->fields as $field) {
            $field->fill($data);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setWidth($field = 8, $label = 2): self
    {
        foreach ($this->fields as $child) {
            $child->setWidth($field, $label);
        }

        return parent::setWidth($field, $label);
    }

    /**
     * Build nested fields once before render.
     */
    protected function build()
    {
        if (!$this->builder) {
            return;
        }

        $form = $this->form;

        if (!$form || !method_exists($form, 'collectFields')) {
            $content = call_user_func($this->builder, $form);
            if (is_string($content)) {
                $this->content = $content;
            }
            $this->builder = null;

            return;
        }

        $form->collectFields(function () use ($form) {
            call_user_func($this->builder, $form);
        }, $this);

        $this->builder = null;

        if ($form && method_exists($form, 'data')) {
            $this->fill($form->data());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $this->build();

        if ($this->content instanceof Closure) {
            $this->content = call_user_func($this->content, $this->form);
        }

        $this->addVariables([
            'title'       => $this->title,
            'description' => $this->description,
            'content'     => $this->content,
            'fields'      => $this->fields,
        ]);

        return parent::render();
    }
}
