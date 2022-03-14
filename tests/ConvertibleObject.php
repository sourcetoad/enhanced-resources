<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests;

use Sourcetoad\EnhancedResources\Resourceable\AsResource;
use Sourcetoad\EnhancedResources\Resourceable\ConvertsToResource;

/**
 * @method ImplicitDefaultResource toResource()
 */
#[AsResource(ImplicitDefaultResource::class)]
class ConvertibleObject
{
    use ConvertsToResource;

    public function __construct(
        protected int $id,
        protected string $firstName,
        protected string $lastName,
    ) {}
}
