<?php

declare(strict_types=1);

namespace App\Tests\Service\Builder;

use App\Entity\FormField;
use App\Service\Builder\FormBuilder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormBuilderTest extends TestCase
{
    private const NAMESPACE = 'App\\Form';
    private const CLASS_NAME = 'TestType';
    private const CLASS_FULL_NAME = self::NAMESPACE . '\\' . self::CLASS_NAME;
    private const TEXT_FIELD = 'foo';
    private const NUMBER_FIELD = 'bar';

    /**
     * @var array the options to try resolving via OptionsResolver
     */
    private $options;

    /**
     * @var FormBuilder the builder to test
     */
    private $builder;

    /**
     * Sets up the builder.
     */
    protected function setUp(): void
    {
        $this->options = [
            'foo' => 'dummy',
            'bar' => 1234,
            'baz' => [
                'nested',
                5678,
            ],
        ];

        $this->builder = (new FormBuilder(self::CLASS_NAME))
            ->addField(
                (new FormField(TextType::class, self::TEXT_FIELD))
            )
            ->addField(
                (new FormField(NumberType::class, self::NUMBER_FIELD))
            )
            ->setOptions($this->options)
        ;
    }

    /**
     * Tests to build an entity.
     */
    public function testBuild(): void
    {
        eval($this->builder->build());

        $class = self::CLASS_FULL_NAME;
        $entity = new $class;
        $this->assertInstanceOf($class, $entity);

        call_user_func([$entity, 'buildForm'], $this->getFormBuilder(), []);
        call_user_func([$entity, 'configureOptions'], $this->getOptionsResolver());
    }

    /**
     * Gets the prophecy of FormBuilder.
     *
     * @return FormBuilderInterface the created prophecy
     */
    private function getFormBuilder(): FormBuilderInterface
    {
        $formBuilderP = $this->prophesize(FormBuilderInterface::class);
        $formBuilder = $formBuilderP->reveal();

        $formBuilderP
            ->add(self::TEXT_FIELD, TextType::class, [])
            ->willReturn($formBuilder)
            ->shouldBeCalledOnce()
        ;

        $formBuilderP
            ->add(self::NUMBER_FIELD, NumberType::class, [])
            ->willReturn($formBuilder)
            ->shouldBeCalledOnce()
        ;

        return $formBuilder;
    }

    /**
     * Gets the prophecy of OptionsResolver.
     *
     * @return OptionsResolver the created prophecy
     */
    private function getOptionsResolver(): OptionsResolver
    {
        $optionsResolver = $this->prophesize(OptionsResolver::class);

        $optionsResolver
            ->setDefaults($this->options)
            ->shouldBeCalledOnce()
        ;

        return $optionsResolver->reveal();
    }
}
