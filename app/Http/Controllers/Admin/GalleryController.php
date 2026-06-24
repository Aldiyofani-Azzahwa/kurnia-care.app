<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'title.required' => 'Judul dokumentasi wajib diisi.',
            'image.required' => 'Gambar dokumentasi wajib diunggah.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus jpg, jpeg, png, atau webp.',
            'image.max' => 'Ukuran gambar maksimal 2 MB.',
        ]);

        Gallery::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'image' => $request->file('image')->store('galleries', 'public'),
            'is_active' => $request->boolean('is_active'),
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

    public function update(Request $request, Gallery $gallery): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'title.required' => 'Judul dokumentasi wajib diisi.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus jpg, jpeg, png, atau webp.',
            'image.max' => 'Ukuran gambar maksimal 2 MB.',
        ]);

        $imagePath = $gallery->image;

        if ($request->hasFile('image')) {
            $this->deleteGalleryImage($gallery);

            $imagePath = $request->file('image')->store('galleries', 'public');
        }

        $gallery->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'image' => $imagePath,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.galleries.index')
            ->with('success', 'Dokumentasi berhasil diperbarui.');
    }

    public function destroy(Gallery $gallery): RedirectResponse
    {
        $this->deleteGalleryImage($gallery);

        $gallery->delete();

        return redirect()
            ->route('admin.galleries.index')
            ->with('success', 'Dokumentasi berhasil dihapus.');
    }

    private function deleteGalleryImage(Gallery $gallery): void
    {
        if ($gallery->image && Storage::disk('public')->exists($gallery->image)) {
            Storage::disk('public')->delete($gallery->image);
        }
    }
}