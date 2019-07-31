<?php

declare(strict_types=1);

namespace App\Service\Builder;

use Nette\PhpGenerator\PhpNamespace;

interface BuilderInterface
{
    /**
     * Builds the component.
     *
     * @return PhpNamespace the namespace with the built class
     */
    public function build(): PhpNamespace;
}
