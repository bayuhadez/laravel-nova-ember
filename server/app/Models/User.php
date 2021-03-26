<?php

namespace App\Models;

use App\Notifications\MailResetPasswordNotification;
use App\Notifications\VerifyApiEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laratrust\Traits\LaratrustUserTrait;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use HasApiTokens;
    use LaratrustUserTrait;

    /* note: I keep this code as example to extend and modify relation */
    /*{
        LaratrustUserTrait::roles as LaratrustUserTraitRoles;
    }*/

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = [
        'verification_email_sent_at',
    ];

    public function person()
    {
        return $this->hasOne('App\Models\Person');
    }

    public function companies()
    {
        return $this
            ->belongsToMany('App\Models\Company')
            ->withTimestamps();
    }

    public function companyUsers()
    {
        return $this->hasMany('App\Models\CompanyUser');
    }

    public function chats()
    {
        return $this->hasMany('App\Models\Chat');
    }

    public function purchasedProducts()
    {
        return $this
            ->belongsToMany('App\Models\Product')
            ->withTimestamps();
    }

    /**
     * Return Company from session or firstCompany or null if it doesn't have company session
     *
     * @return \App\Models\Company|null
     */
    public function getCurrentCompanyAttribute()
    {
        if (!empty(session('company'))) {
            return session('company');
        } elseif (!empty($this->firstCompany)) {
            return $this->firstCompany;
        }
        return null;
    }

    /**
     * Return current company's id
     * Or return null if user doesn't have current company selected
     *
     * @return integer|null
     */
    public function getCurrentCompanyId()
    {
        if ($this->currentCompany) {
            return $this->currentCompany->id;
        }
        return null;
    }

    public function getCurrentCompanyIdAttribute()
    {
        return $this->getCurrentCompanyId();
    }

    /**
     * Return first related Company or null if empty
     *
     * @return \App\Models\Company|null
     */
    public function getFirstCompanyAttribute()
    {
        return $this->companies->first();
    }

    /**
     * Returns Company from session
     * if it's empty then returns fist related Company
     *
     * @return \App\Models\Company|null
     */
    public function getCurrentOrFirstCompanyAttribute()
    {
        return $this->currentCompany ?? $this->firstCompany;
    }

    /* note: I keep this code as example to extend and modify relation
    public function commonRoles()
    {
        return $this
            ->LaratrustUserTraitRoles()
            ->where('name', '<>', 'superadministrator');
    }
     */

    /**
     * returns the person fullname if exists, otherwise
     * returns the user email
     */
    public function getDisplayNameAttribute()
    {
        return $this->person->fullname ?? $this->email;
    }

    public function hasPurchasedProduct(Product $product)
    {
        return $this->purchasedProducts->contains($product);
    }

    /**
     * Send email to verify email
     */
    public function sendApiEmailVerificationNotification()
    {
        $this->notify(new VerifyApiEmail); // my notification
    }

    /**
     * Return true if now is greater than verification_email_sent_at + 10 minutes
     *
     * @return boolean
     */
    public function isMoreThanTenMinutesAfterLastVerificationEmailSent()
    {
        $verificationEmailSentAt = $this->verification_email_sent_at;

        $verificationEmailSentAt->addMinutes(10);

        $now = Carbon::now();

        return $now->gt($verificationEmailSentAt);
    }

    /**
     * Override the mail body for reset password notification mail.
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MailResetPasswordNotification($token));
    }
}
