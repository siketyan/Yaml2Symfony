<?php

declare(strict_types=1);

namespace App\Tests\Service\Builder;

use App\Entity\Field;
use App\Service\Builder\EntityBuilder;
use PHPUnit\Framework\TestCase;

class EntityBuilderTest extends TestCase
{
    private const NAMESPACE = 'App\\Entity';
    private const CLASS_NAME = 'TestEntity';
    private const CLASS_FULL_NAME = self::NAMESPACE . '\\' . self::CLASS_NAME;
    private const STRING_FIELD = 'foo';
    private const INT_FIELD = 'bar';

    /**
     * @var EntityBuilder the builder to test
     */
    private $builder;

    /**
     * Sets up the builder.
     */
    protected function setUp(): void
    {
        $this->builder = (new EntityBuilder(self::CLASS_NAME))
            ->addField(
                (new Field(Field::TYPE_STRING, self::STRING_FIELD))
            )
            ->addField(
                (new Field(Field::TYPE_INT, self::INT_FIELD))
            )
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

        call_user_func([$entity, 'setFoo'], 'dummy');
        $this->assertEquals('dummy', call_user_func([$entity, 'getFoo']));
    }
}
