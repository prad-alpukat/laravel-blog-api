<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MediaController extends Controller
{
    public function get(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);

        try {
            // Mengambil data dari API WordPress
            $response = Http::get(env("WP_BASE_URL") . "/wp-json/wp/v2/media?_embed&page={$page}&per_page={$perPage}");

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

    public function create(Request $request)
    {
        try {
            $file = $request->file('file');
            $password = env('WP_APP_PASSWORD');

            // Membuat post baru ke WordPress menggunakan Basic Auth
            $response = Http::withBasicAuth('admin', $password)
                ->attach('file', file_get_contents($file), $file->getClientOriginalName())
                ->post(env("WP_BASE_URL") . "/wp-json/wp/v2/media", [
                    'title' => $file->getClientOriginalName(),
                    'caption' => $request->input('caption', ''),
                    'alt_text' => $request->input('alt_text', ''),
                    'description' => $request->input('description', ''),
                ]);

            // Memeriksa apakah permintaan berhasil (status kode 201)
            if ($response->status() == 201) {
                $data = $response->json();
                return response()->json(['success' => true, 'data' => $data]);
            } else {
                return response()->json(['errors' => ['message' => 'Gagal mengunggah media.', "status" => $response->status()]], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json(['errors' => ['message' => $e->getMessage(), "status" => 500]], 500);
        }
    }

    public function delete($id)
    {
        try {
            $password = env('WP_APP_PASSWORD');

            // Menghapus media dari WordPress menggunakan Basic Auth
            $response = Http::withBasicAuth('admin', $password)
                ->delete(env("WP_BASE_URL") . "/wp-json/wp/v2/media/{$id}?force=true");

            // Memeriksa apakah permintaan berhasil (status kode 200)
            if ($response->successful()) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['errors' => ['message' => 'Gagal menghapus media.', "status" => $response->status()]], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json(['errors' => ['message' => $e->getMessage(), "status" => 500]], 500);
        }
    }
}
