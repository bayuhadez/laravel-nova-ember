<?php

namespace Tests\Unit\Services;

use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Collection;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Services\OrderService
 */
class OrderServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @covers ::getStatusOptions
     */
    public function testGetStatusOptions()
    {
        // ARRANGE
        $service = new OrderService;

        // ASSERT
        $this->assertInstanceOf(
            Collection::class,
            $service->getStatusOptions()
        );
    }

}
