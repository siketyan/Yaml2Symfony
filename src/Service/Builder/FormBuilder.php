<?php

declare(strict_types=1);

namespace App\Service\Builder;

use App\Entity\FormField;
use App\Template\FormTemplate;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpLiteral;
use Nette\PhpGenerator\PhpNamespace;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormBuilder implements BuilderInterface
{
    private const NAMESPACE = 'App\\Form';
    private const TYPES_NAMESPACE = 'Symfony\\Component\\Form\\Extension\\Core\\Type';
    private const TYPES_ALIAS = 'Form';

    /**
     * @var string the name of the entity
     */
    private $name;

    /**
     * @var FormField[] the fields in the form
     */
    private $fields;

    /**
     * @var array the options of the form
     */
    private $options;

    /**
     * FormBuilder constructor.
     *
     * @param string $name the name of the form
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->fields = [];
        $this->options = [];
    }

    /**
     * {@inheritdoc}
     */
    public function build(): PhpNamespace
    {
        $namespace = (new PhpNamespace(self::NAMESPACE))
            ->addUse(AbstractType::class)
            ->addUse(FormBuilderInterface::class)
            ->addUse(OptionsResolver::class)
            ->addUse(self::TYPES_NAMESPACE, self::TYPES_ALIAS)
            ->add(
                $class =
                    ClassType::from(FormTemplate::class)
                        ->setName($this->getName())
            )
        ;

        $this->buildMethodBuildForm($namespace, $class);
        $this->buildMethodConfigureOptions($class);

        return $namespace;
    }

    /**
     * Builds `buildForm` method.
     *
     * @see FormTemplate::buildForm()
     *
     * @param PhpNamespace $namespace the namespace that the form is in
     * @param ClassType    $class     the class of the form
     */
    private function buildMethodBuildForm(PhpNamespace $namespace, ClassType $class): void
    {
        $method = $class
            ->getMethod('buildForm')
                ->addComment('{@inheritdoc}')
                ->addBody('$builder')
        ;

        foreach ($this->getFields() as $field) {
            $method
                ->addBody('    ->add(?, ?::class, ?)', [
                    $field->getName(),
                    new PhpLiteral(
                        $namespace->unresolveName($field->getType())
                    ),
                    $field->getOptions(),
                ])
            ;
        }

        $method->addBody(';');
    }

    /**
     * Builds `configureOptions` method.
     *
     * @see FormTemplate::configureOptions()
     *
     * @param ClassType $class the class of the form
     */
    private function buildMethodConfigureOptions(ClassType $class): void
    {
        $class
            ->getMethod('configureOptions')
                ->addComment('{@inheritdoc}')
                ->addBody('$resolver->setDefaults(?);',[$this->getOptions()])
        ;
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
     * @return FormField[] the fields in the entity
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @param FormField $field the field to add
     *
     * @return self
     */
    public function addField(FormField $field): self
    {
        $this->fields[] = $field;

        return $this;
    }

    /**
     * @return array the options of the form
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array $options the options of the form
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
