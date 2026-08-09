<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Fixtures\Reflection;

#[ChildAttribute, ParentAttribute, ChildAttribute]
final class MixedOrderAttributesClass
{
    #[ChildAttribute, ParentAttribute, ParentAttribute, ChildAttribute]
    public function foo(): void
    {
        //
    }
}
