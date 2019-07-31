<?php

declare(strict_types=1);

namespace App\Service\Builder;

use App\Entity\Field;
use App\Service\NameFormatter;
use Nette\PhpGenerator\Parameter;
use Nette\PhpGenerator\PhpNamespace;

class EntityBuilder implements BuilderInterface
{
    private const NAMESPACE = 'App\\Entity';
    private const PROPERTY_VISIBILITY = 'private';
    private const GETTER_PREFIX = 'get';
    private const SETTER_PREFIX = 'set';

    /**
     * @var string the name of the entity
     */
    private $name;

    /**
     * @var Field[] the fields in the entity
     */
    private $fields;

    /**
     * EntityBuilder constructor.
     *
     * @param string $name the name of the entity
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->fields = [];
    }

    /**
     * {@inheritdoc}
     */
    public function build(): PhpNamespace
    {
        $namespace = new PhpNamespace(self::NAMESPACE);
        $class = $namespace->addClass($this->getName());

        foreach ($this->getFields() as $field)
        {
            $name = NameFormatter::toCamelCase($field->getName(), false);
            $upperName = NameFormatter::toCamelCase($field->getName());

            $type = $field->getType();

            $class
                ->addProperty($name)
                    ->setVisibility(self::PROPERTY_VISIBILITY)
                    ->addComment('@var ' . $type)
            ;

            $class
                ->addMethod(self::GETTER_PREFIX . $upperName)
                    ->setReturnType($type)
                    ->addBody('return $this->?;', [$name])
                    ->addComment('@return ' . $type)
            ;

            $class
                ->addMethod(self::SETTER_PREFIX . $upperName)
                    ->setReturnType('self')
                    ->setParameters([
                        (new Parameter($name))
                            ->setTypeHint($type),
                    ])
                    ->addBody('$this->? = $?;', [$name, $name])
                    ->addBody('return $this;')
                    ->addComment('@return self')
            ;
        }

        $namespace->add($class);

        return $namespace;
    }

    /**
     * @return string the name of the entity
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name the name of the entity
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Field[] the fields in the entity
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @param Field $field the field to add
     *
     * @return self
     */
    public function addField(Field $field): self
    {
        $this->fields[] = $field;

        return $this;
    }
}
