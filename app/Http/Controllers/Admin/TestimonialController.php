<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Services\SupabaseStorageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function index(): View
    {
        $testimonials = Testimonial::latest()->paginate(10);

        return view('admin.testimonials.index', [
            'testimonials' => $testimonials,
        ]);
    }

    public function create(): View
    {
        return view('admin.testimonials.create');
    }

    public function store(Request $request, SupabaseStorageService $storage): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'content' => ['required', 'string'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'content.required' => 'Isi testimoni wajib diisi.',
            'rating.required' => 'Rating wajib diisi.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus jpg, jpeg, png, atau webp.',
            'image.max' => 'Ukuran gambar maksimal 2 MB.',
        ]);

        $imageUrl = $this->storeImage($request, $storage);

        Testimonial::create([
            'name' => $validated['name'],
            'content' => $validated['content'],
            'rating' => $validated['rating'],
            'image' => $imageUrl,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', 'Testimoni berhasil ditambahkan.');
    }

    public function edit(Testimonial $testimonial): View
    {
        return view('admin.testimonials.edit', [
            'testimonial' => $testimonial,
        ]);
    }

    public function update(
        Request $request,
        Testimonial $testimonial,
        SupabaseStorageService $storage
    ): RedirectResponse {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'content' => ['required', 'string'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_image' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'content.required' => 'Isi testimoni wajib diisi.',
            'rating.required' => 'Rating wajib diisi.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus jpg, jpeg, png, atau webp.',
            'image.max' => 'Ukuran gambar maksimal 2 MB.',
        ]);

        $imageUrl = $testimonial->image;

        if ($request->boolean('remove_image')) {
            $this->deleteImage($testimonial, $storage);
            $imageUrl = null;
        }

        if ($request->hasFile('image')) {
            $this->deleteImage($testimonial, $storage);
            $imageUrl = $this->storeImage($request, $storage);
        }

        $testimonial->update([
            'name' => $validated['name'],
            'content' => $validated['content'],
            'rating' => $validated['rating'],
            'image' => $imageUrl,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', 'Testimoni berhasil diperbarui.');
    }

    public function destroy(
        Testimonial $testimonial,
        SupabaseStorageService $storage
    ): RedirectResponse {
        $this->deleteImage($testimonial, $storage);

        $testimonial->delete();

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', 'Testimoni berhasil dihapus.');
    }

    private function storeImage(Request $request, SupabaseStorageService $storage): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        return $storage->upload($request->file('image'), 'testimonials');
    }

    private function deleteImage(Testimonial $testimonial, SupabaseStorageService $storage): void
    {
        if (! $testimonial->image) {
            return;
        }

        if (str_starts_with($testimonial->image, 'http://') || str_starts_with($testimonial->image, 'https://')) {
            $storage->delete($testimonial->image);
            return;
        }

        if (Storage::disk('public')->exists($testimonial->image)) {
            Storage::disk('public')->delete($testimonial->image);
        }
    }
}