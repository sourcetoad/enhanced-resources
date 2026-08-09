<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Fixtures\Reflection;

#[ChildAttribute, ChildAttribute]
final class ChildAttributesClass
{
    #[ChildAttribute, ChildAttribute, ChildAttribute]
    public function foo(): void
    {
        //
    }
}
