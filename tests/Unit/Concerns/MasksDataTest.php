<?php


namespace Sourcetoad\EnhancedResources\Tests\Unit\Concerns;

use Orchestra\Testbench\TestCase;
use Sourcetoad\EnhancedResources\Tests\User;
use Sourcetoad\EnhancedResources\Tests\UserResource;

class MasksDataTest extends TestCase
{
    /** @var User */
    protected $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = new User([
            'email_address' => 'test@example.com',
            'first_name'    => 'John',
            'id'            => 1,
            'last_name'     => 'Doe',
            'password'      => 'CorrectBatteryHorseStaple'
        ]);
    }

    public function testMasksAreApplied()
    {
        $data = UserResource::make($this->model)
            ->mask()
            ->resolve();

        $this->assertSame('t*****@example.com', $data['email_address']);
        $this->assertSame('*****', $data['password']);
    }

    public function testMasksCanBeRemoved()
    {
        $data = UserResource::make($this->model)
            ->mask()
            ->unmask()
            ->resolve();

        $this->assertSame('test@example.com', $data['email_address']);
        $this->assertSame('CorrectBatteryHorseStaple', $data['password']);
    }
}
