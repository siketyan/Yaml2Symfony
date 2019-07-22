<?php

declare(strict_types=1);

namespace App\Service\Builder;

use Nette\PhpGenerator\ClassType;

interface BuilderInterface
{
    public function build(): ClassType;
}
