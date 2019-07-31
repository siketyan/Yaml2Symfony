<?php

declare(strict_types=1);

namespace App\Entity;

class Field
{
    public const TYPE_STRING = 'string';
    public const TYPE_INT = 'int';
    public const TYPE_FLOAT = 'float';
    public const TYPE_BOOL = 'bool';
    public const TYPE_ARRAY = 'array';

    /**
     * @var string the type of the field
     */
    private $type;

    /**
     * @var string the name of the field
     */
    private $name;

    /**
     * Field constructor.
     *
     * @param string $type the type of the field
     * @param string $name the name of the field
     */
    public function __construct(string $type, string $name)
    {
        $this->type = $type;
        $this->name = $name;
    }

    /**
     * @return string the type of the field
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type the type of the field
     *
     * @return self
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string the name of the field
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name the name of the field
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
