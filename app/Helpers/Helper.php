<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class Helper
{
    public function get_user_id($name)
    {
        $response = Http::get(env("WP_BASE_URL") . "/wp-json/wp/v2/users?search={$name}");
        $author = $response->json();
        if (count($author) > 0) {
            return $author[0]['id'];
        } else {
            return null;
        }
    }

    public function get_user($name)
    {
        $response = Http::get(env("WP_BASE_URL") . "/wp-json/wp/v2/users?search={$name}");
        $author = $response->json();

        if (count($author) > 0) {
            return $author[0];
        } else {
            return null;
        }
    }
}
