<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Enhancements\Traits;

use Sourcetoad\EnhancedResources\Enhancements\Only;

trait HasOnlyEnhancement
{
    public function only(...$keys): static
    {
        return $this->modify(new Only($keys));
    }
}
