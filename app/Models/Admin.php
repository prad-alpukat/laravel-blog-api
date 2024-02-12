<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model implements Authenticatable
{
    protected $table = "admins";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $timestamp = true;
    public $incrementing = true;


    // Implement the required methods from the Authenticatable interface
    public function getAuthIdentifierName()
    {
        return 'username';
    }

    public function getAuthIdentifier()
    {
        return $this->username;
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberToken()
    {
        return $this->token;
    }

    public function setRememberToken($value)
    {
        $this->token = $value;
    }

    public function getRememberTokenName()
    {
        return 'token';
    }
}
