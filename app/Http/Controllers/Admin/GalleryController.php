<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Services\SupabaseStorageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(): View
    {
        $galleries = Gallery::latest()->paginate(10);

        return view('admin.galleries.index', [
            'galleries' => $galleries,
        ]);
    }

    public function create(): View
    {
        return view('admin.galleries.create');
    }

    public function store(Request $request, SupabaseStorageService $storage): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'title.required' => 'Judul dokumentasi wajib diisi.',
            'image.required' => 'Foto dokumentasi wajib diupload.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus jpg, jpeg, png, atau webp.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $imageUrl = $storage->upload($request->file('image'), 'galleries');

        Gallery::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'image' => $imageUrl,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('admin.galleries.index')
            ->with('success', 'Dokumentasi berhasil ditambahkan.');
    }

    public function edit(Gallery $gallery): View
    {
        return view('admin.galleries.edit', [
            'gallery' => $gallery,
        ]);
    }

    public function update(Request $request, Gallery $gallery, SupabaseStorageService $storage): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'title.required' => 'Judul dokumentasi wajib diisi.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus jpg, jpeg, png, atau webp.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $imageUrl = $gallery->image;

        if ($request->hasFile('image')) {
            $storage->delete($gallery->image);
            $imageUrl = $storage->upload($request->file('image'), 'galleries');
        }

        $gallery->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'image' => $imageUrl,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.galleries.index')
            ->with('success', 'Dokumentasi berhasil diperbarui.');
    }

    public function destroy(Gallery $gallery, SupabaseStorageService $storage): RedirectResponse
    {
        $storage->delete($gallery->image);

        $gallery->delete();

        return redirect()
            ->route('admin.galleries.index')
            ->with('success', 'Dokumentasi berhasil dihapus.');
    }
}