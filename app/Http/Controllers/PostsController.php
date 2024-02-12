<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class PostsController extends Controller
{
    public function get(Request $request): JsonResponse
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);

        try {
            // Mengambil data dari API WordPress
            $response = Http::get(env("WP_BASE_URL") . "/wp-json/wp/v2/posts?_embed&page={$page}&per_page={$perPage}");

            // Memeriksa apakah permintaan berhasil (status kode 200)
            if ($response->successful()) {
                $data = $response->json();
                // return data and total page to client
                return response()->json(['success' => true, 'data' => $data, 'total_page' => $response->header('X-WP-TotalPages')]);
            } else {
                return response()->json(['errors' => ['message' => 'Gagal mengambil data.', "status" => $response->status()]], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json(['errors' => ['message' => $e->getMessage(), "status" => 500]], 500);
        }
    }

    public function getWordPressPostById($id)
    {
        try {
            // Mengambil data dari API WordPress berdasarkan ID
            $response = Http::get(env("WP_BASE_URL") . "/wp-json/wp/v2/posts/{$id}?_embed");

            // Memeriksa apakah permintaan berhasil (status kode 200)
            if ($response->successful()) {
                $data = $response->json();
                return response()->json(['success' => true, 'data' => $data]);
            } else {
                return response()->json(['errors' => ['message' => 'Gagal mengambil data.', "status" => $response->status()]], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json(['errors' => ['message' => $e->getMessage(), "status" => 500]], 500);
        }
    }

    public function createWordPressPost(Request $request)
    {
        try {
            $data = $request->all();
            $password = env('WP_APP_PASSWORD');
            $data['status'] = 'publish';

            // Membuat post baru ke WordPress menggunakan Basic Auth
            $response = Http::withBasicAuth('admin', $password)
                ->post(env("WP_BASE_URL") . "/wp-json/wp/v2/posts", $data);

            // Memeriksa apakah permintaan berhasil (status kode 201)
            if ($response->status() === 201) {
                $data = $response->json();
                return response()->json(['success' => true, 'data' => $data]);
            } else {
                // dd($response);
                return response()->json(['success' => false, 'message' => 'Gagal membuat post.'], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateWordPressPost(Request $request, $id)
    {
        try {
            $data = $request->all();
            $password = env('WP_APP_PASSWORD');

            // Melakukan pembaruan post WordPress menggunakan Basic Auth
            $response = Http::withBasicAuth('admin', $password)
                ->put(env("WP_BASE_URL") . "/wp-json/wp/v2/posts/{$id}", [
                    ...$data
                ]);

            // Memeriksa apakah permintaan berhasil (status kode 200)
            if ($response->successful()) {
                $data = $response->json();
                return response()->json(['success' => true, 'data' => $data]);
            } else {
                return response()->json(['success' => false, 'message' => 'Gagal melakukan pembaruan post.'], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteWordPressPost($id)
    {
        try {
            $password = env('WP_APP_PASSWORD');

            // Menghapus post WordPress menggunakan Basic Auth
            $response = Http::withBasicAuth('admin', $password)
                ->delete(env("WP_BASE_URL") . "/wp-json/wp/v2/posts/{$id}");

            // Memeriksa apakah permintaan berhasil (status kode 200)
            if ($response->successful()) {
                return response()->json(['success' => true, 'message' => 'Post berhasil dihapus.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Gagal menghapus post.'], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
