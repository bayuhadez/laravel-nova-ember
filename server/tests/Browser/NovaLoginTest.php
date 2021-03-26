<?php

namespace Tests\Browser;

use App\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class NovaLoginTest extends DuskTestCase
{
	use DatabaseMigrations;

    /**
     * Test login nova admin page
     *
     * @return void
     */
    public function testNovaLogin()
    {
		$user = factory(User::class)->create([
			'password' => bcrypt('secret')
		]);

        $this->browse(function (Browser $browser) use ($user) {
			//$browser->loginAs($user)->firstOrFails();
            $browser->visit('/nova/login')
					->type('email', $user->email)
					->type('password', 'secret')
					->press('Login')
					->assertDontSee('These credentials do not match our records.');
        });
    }
}
