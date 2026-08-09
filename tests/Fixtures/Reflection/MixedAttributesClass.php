<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Fixtures\Reflection;

#[ParentAttribute, ChildAttribute, ChildAttribute]
final class MixedAttributesClass
{
    #[ParentAttribute, ParentAttribute, ChildAttribute, ChildAttribute]
    public function foo(): void
    {
        //
    }
}
