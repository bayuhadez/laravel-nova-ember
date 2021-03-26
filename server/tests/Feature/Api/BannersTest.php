<?php

namespace Tests\Feature\Api;

use App\Models\Banner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\Feature\TestCase;

class BannersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var string
     */
    protected $resourceType = 'banners';

    /**
     * @test
     */
    public function read()
    {
        // ARRANGE
        $banner = factory(Banner::class)->state('image')->create();

        // ASSERT
        $this
            ->doRead($banner)
            ->assertJson($this->serialize($banner));
    }

    private function serialize(Banner $banner)
    {
        // it will produce (string) https://web:8080/api/v1/banners/{$id}
        $self = "{$this->apiUrl}/{$this->resourceType}/{$banner->getKey()}";

        return [
            'data' => [
                'type' => $this->resourceType,
                'id' => (string) $banner->getRouteKey(),
                'attributes' => [
                    'image' => $banner->image,
                ],
            ]
        ];
    }

}
