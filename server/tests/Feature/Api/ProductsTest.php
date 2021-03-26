<?php

namespace Tests\Feature\Api;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\Feature\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var string
     */
    protected $resourceType = 'products';

    /**
     * @test
     */
    public function read()
    {
        // ARRANGE
        $product = factory(Product::class)->create();

        // ASSERT
        $this
            ->doRead($product)
            ->assertJson($this->serialize($product));
    }

    /**
     * @test
     */
    public function homeReturnsCountExpectedNumberOfData()
    {
        // ARRANGE
        factory(Product::class, 14)->create([
            'company_id' => config('app.default_company_id'),
            'status' => Product::STATUS_APPROVED,
        ]);

        // ASSERT
        $this
            ->doSearch($this->getHomeUriParams())
            ->assertJsonCount(10, 'data');
    }

    private function serialize(Product $product)
    {
        // (string) https://web:8080/api/v1/products/{$id}
        $self = "{$this->apiUrl}/{$this->resourceType}/{$product->getKey()}";

        return [
            'data' => [
                'type' => $this->resourceType,
                'id' => (string) $product->getRouteKey(),
                'attributes' => [
                    'image' => $product->image,
                    'name' => $product->name,
                    'description' => $product->description,
                    'price' => $product->price,
                    'status' => $product->status,
                ],
                'relationships' => [
                    'product-category' => [
                        'links' => [
                            'self' => "{$self}/relationships/product-category",
                            'related' => $self."/product-category"
                        ]
                    ],
                    'product-banner' => [
                        'links' => [
                            'self' => "{$self}/relationships/product-banner",
                            'related' => $self."/product-banner"
                        ]
                    ],
                    'company' => [
                        'links' => [
                            'self' => $self."/relationships/company",
                            'related' => $self."/company"
                        ]
                    ],
                    'user' => [
                        'links' => [
                            'self' => $self."/relationships/user",
                            'related' => $self."/user"
                        ]
                    ],
                    'seminar-product-meta' => [
                        'links' => [
                            'self' => $self."/relationships/seminar-product-meta",
                            'related' => $self."/seminar-product-meta"
                        ]
                    ],
                    'chat-rooms' => [
                        'links' => [
                            'self' => $self."/relationships/chat-rooms",
                            'related' => $self."/chat-rooms"
                        ]
                    ]
                ],
                'links' => [
                    'self' => $self,
                ]
            ]
        ];
    }

    private function getHomeUriParams(): array
    {
        return [
            'fields' => ['seminar-product-metas' => 'start-time'],
            'include' => 'seminar-product-meta.speaker,user',
            'page' => [
                'number' => 1,
                'size' => 10,
            ],
            'sort' => '-created-at',
        ];
    }
}
