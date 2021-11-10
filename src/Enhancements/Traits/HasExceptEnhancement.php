<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Enhancements\Traits;

use Sourcetoad\EnhancedResources\Enhancements\Except;

trait HasExceptEnhancement
{
    public function except(...$keys): static
    {
        return $this->modify(new Except($keys));
    }
}
