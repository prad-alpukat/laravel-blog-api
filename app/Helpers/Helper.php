<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class Helper
{
    public function get_user_id($name)
    {
        $response = Http::get(env("WP_BASE_URL") . "/wp-json/wp/v2/users?search={$name}");
        $author = $response->json();
        return $author[0]['id'];
    }
}
