<?php

namespace Sourcetoad\EnhancedResources\Tests\Unit;

use Orchestra\Testbench\TestCase;
use Sourcetoad\EnhancedResources\EnhancedAnonymousResourceCollection;
use Sourcetoad\EnhancedResources\EnhancedResource;
use Sourcetoad\EnhancedResources\Tests\User;
use Sourcetoad\EnhancedResources\Tests\UserResource;

class EnhancedCollectionTest extends TestCase
{
    /** @var EnhancedAnonymousResourceCollection */
    protected $collection;

    protected function setUp(): void
    {
        parent::setUp();

        $this->collection = UserResource::collection(collect([
            new User([
                'email_address' => 'john.doe@example.com',
                'first_name' => 'John',
                'id' => 1,
                'last_name' => 'Doe',
                'password' => 'CorrectBatteryHorseStaple'
            ]),
            new User([
                'email_address' => 'jane.doe@example.com',
                'first_name' => 'Jane',
                'id' => 2,
                'last_name' => 'Doe',
                'password' => 'CorrectBatteryHorseStaple'
            ])
        ]));
    }

    public function testAppendWorksOnCollections()
    {
        // Act
        $data = $this->collection
            ->append('full_name')
            ->resolve();

        // Assert
        $this->assertNotEmpty($data);

        foreach ($data as $datum) {
            $this->assertArrayHasKey('full_name', $datum);
        }
    }

    public function testExcludeWorksOnCollections()
    {
        // Act
        $data = $this->collection
            ->exclude('password')
            ->resolve();

        // Assert
        $this->assertNotEmpty($data);

        foreach ($data as $datum) {
            $this->assertArrayNotHasKey('password', $datum);
        }
    }

    public function testMaskWorksOnCollections()
    {
        // Act
        $data = $this->collection->mask()->resolve();

        // Assert
        $this->assertNotEmpty($data);

        foreach ($data as $datum) {
            $this->assertSame('j*****@example.com', $datum['email_address']);
            $this->assertSame('*****', $datum['password']);
        }
    }

    public function testOnlyWorksOnCollections()
    {
        // Act
        $data = $this->collection
            ->only('id')
            ->resolve();

        // Assert
        $this->assertNotEmpty($data);

        foreach ($data as $datum) {
            $this->assertArrayHasKey('id', $datum);
            $this->assertCount(1, $datum);
        }
    }

    public function testCustomHookCanBeAppliedToCollection()
    {
        // Arrange
        $hasChildren = [
            1 => false,
            2 => true
        ];

        // Act
        $data = $this->collection
            ->customHook(function (EnhancedResource $resource, array $data) use ($hasChildren) {
                $data['has_children'] = $hasChildren[$resource->resource->getKey()];

                return $data;
            })
            ->resolve();

        // Assert
        $this->assertNotEmpty($data);

        foreach ($data as $datum) {
            $this->assertArrayHasKey('has_children', $datum);
            $this->assertSame($hasChildren[$datum['id']], $datum['has_children']);
        }
    }
}
