<?php

declare(strict_types=1);

namespace App\Entity;

class FormField extends Field
{
    /**
     * @var array the options of the form field
     */
    private $options;

    /**
     * FormField constructor.
     *
     * @param string $type    the type of the form field
     * @param string $name    the name of the form field
     * @param array  $options the options of the form field
     */
    public function __construct(string $type, string $name, array $options)
    {
        parent::__construct($type, $name);
    }

    /**
     * @return array the options of the form field
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array $options the options of the form field
     *
     * @return self
     */
    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @param mixed $option the option to add
     *
     * @return self
     */
    public function addOption($option): self
    {
        $this->options[] = $option;

        return $this;
    }
}
