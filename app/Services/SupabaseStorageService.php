<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class SupabaseStorageService
{
    public function upload(UploadedFile $file, string $folder = 'uploads'): string
    {
        $url = rtrim((string) config('supabase.url'), '/');
        $key = (string) config('supabase.service_role_key');
        $bucket = (string) config('supabase.storage_bucket');

        if (! $url || ! $key || ! $bucket) {
            throw new RuntimeException('Konfigurasi Supabase Storage belum lengkap.');
        }

        $extension = $file->getClientOriginalExtension() ?: 'jpg';

        $filename = $folder . '/' . now()->format('YmdHis') . '-' . Str::random(20) . '.' . $extension;

        $uploadUrl = "{$url}/storage/v1/object/{$bucket}/{$filename}";

        $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $key,
                'apikey' => $key,
                'Content-Type' => $file->getMimeType() ?: 'application/octet-stream',
                'x-upsert' => 'true',
            ])
            ->withBody(
                file_get_contents($file->getRealPath()),
                $file->getMimeType() ?: 'application/octet-stream'
            )
            ->put($uploadUrl);

        if (! $response->successful()) {
            throw new RuntimeException('Upload ke Supabase Storage gagal: ' . $response->body());
        }

        return "{$url}/storage/v1/object/public/{$bucket}/{$filename}";
    }

    public function delete(?string $publicUrl): void
    {
        if (! $publicUrl) {
            return;
        }

        $url = rtrim((string) config('supabase.url'), '/');
        $key = (string) config('supabase.service_role_key');
        $bucket = (string) config('supabase.storage_bucket');

        if (! $url || ! $key || ! $bucket) {
            return;
        }

        $prefix = "{$url}/storage/v1/object/public/{$bucket}/";

        if (! str_starts_with($publicUrl, $prefix)) {
            return;
        }

        $path = str_replace($prefix, '', $publicUrl);

        Http::withHeaders([
                'Authorization' => 'Bearer ' . $key,
                'apikey' => $key,
            ])
            ->delete("{$url}/storage/v1/object/{$bucket}/{$path}");
    }
}