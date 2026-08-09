<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Fixtures\Reflection;

#[ParentAttribute]
final class ParentAttributesClass
{
    #[ParentAttribute, ParentAttribute]
    public function foo(): void
    {
        //
    }
}
