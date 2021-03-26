<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModelFactoryTest extends TestCase
{
    /**
     * Assert based on model class
     * @param $modelClass
     * @return void
     */
    protected function assertInstanceOfModel($modelClass)
    {
        $this->assertInstanceOf($modelClass, factory($modelClass)->create());
    }

    /**
     * @test
     */
    public function createChatFactory()
    {
        $this->assertInstanceOfModel(\App\Models\Chat::class);
    }

    /**
     * @test
     */
    public function createChatRoomFactory()
    {
        $this->assertInstanceOfModel(\App\Models\ChatRoom::class);
    }

    /**
     * @test
     */
    public function createFaqFactory()
    {
        $this->assertInstanceOfModel(\App\Models\Faq::class);
    }

    /**
     * @test
     */
    public function createMidtransTransactionFactory()
    {
        $this->assertInstanceOfModel(\App\Models\MidtransTransaction::class);
    }

    /**
     * @test
     */
    public function createPersonFactory()
    {
        $this->assertInstanceOfModel(\App\Models\Person::class);
    }

    /**
     * @test
     */
    public function createProductBannerFactory()
    {
        $this->assertInstanceOfModel(\App\Models\ProductBanner::class);
    }

    /**
     * @test
     */
    public function createProductCategoryFactory()
    {
        $this->assertInstanceOfModel(\App\Models\ProductCategory::class);
    }

    /**
     * @test
     */
    public function createProductFactory()
    {
        $this->assertInstanceOfModel(\App\Models\Product::class);
    }

    /**
     * @test
     */
    public function createSeminarProductMetaFactory()
    {
        $this->assertInstanceOfModel(\App\Models\SeminarProductMeta::class);
    }

    /**
     * @test
     */
    public function createSeminarProductSponsorFactory()
    {
        $this->assertInstanceOfModel(\App\Models\SeminarProductSponsor::class);
    }

    /**
     * @test
     */
    public function createSponsorFactory()
    {
        $this->assertInstanceOfModel(\App\Models\Sponsor::class);
    }

    /**
     * @test
     */
    public function createVoucherFactory()
    {
        $this->assertInstanceOfModel(\App\Models\Voucher::class);
    }
}
