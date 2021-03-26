<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\TestCase;

class VerifyUserEmailTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * @test
     */
    public function verifyUserEmail()
    {
        // ARRANGE
        $user = factory(User::class)->create();
        $cid = Crypt::encrypt($user->id);
        $cem = Crypt::encrypt($user->email);

        // ACT
        $response = $this->get("api/v1/auth/email/verify/$cid/$cem");

        // ASSERT
        $response->assertRedirect(
            config('app.url_frontend').'/landing/verified-email'
        );
    }

}
