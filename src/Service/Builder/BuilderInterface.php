<?php

declare(strict_types=1);

namespace App\Service\Builder;

use Nette\PhpGenerator\ClassType;

interface BuilderInterface
{
    /**
     * Builds the component.
     *
     * @return ClassType built class
     */
    public function build(): ClassType;
}
