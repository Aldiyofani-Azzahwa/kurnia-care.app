<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::latest()
            ->paginate(10);

        return view('admin.services.index', [
            'services' => $services,
        ]);
    }

    public function create(): View
    {
        return view('admin.services.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_minutes' => ['required', 'integer', 'min:1', 'max:600'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Nama layanan wajib diisi.',
            'price.required' => 'Harga layanan wajib diisi.',
            'price.numeric' => 'Harga layanan harus berupa angka.',
            'price.min' => 'Harga layanan tidak boleh minus.',
            'duration_minutes.required' => 'Durasi layanan wajib diisi.',
            'duration_minutes.integer' => 'Durasi harus berupa angka.',
            'duration_minutes.min' => 'Durasi minimal 1 menit.',
            'duration_minutes.max' => 'Durasi maksimal 600 menit.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus jpg, jpeg, png, atau webp.',
            'image.max' => 'Ukuran gambar maksimal 2 MB.',
        ]);

        Service::create([
            'name' => $validated['name'],
            'slug' => $this->generateUniqueSlug($validated['name']),
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'duration_minutes' => $validated['duration_minutes'],
            'image' => $this->storeServiceImage($request),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit(Service $service): View
    {
        return view('admin.services.edit', [
            'service' => $service,
        ]);
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_minutes' => ['required', 'integer', 'min:1', 'max:600'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_image' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Nama layanan wajib diisi.',
            'price.required' => 'Harga layanan wajib diisi.',
            'price.numeric' => 'Harga layanan harus berupa angka.',
            'price.min' => 'Harga layanan tidak boleh minus.',
            'duration_minutes.required' => 'Durasi layanan wajib diisi.',
            'duration_minutes.integer' => 'Durasi harus berupa angka.',
            'duration_minutes.min' => 'Durasi minimal 1 menit.',
            'duration_minutes.max' => 'Durasi maksimal 600 menit.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus jpg, jpeg, png, atau webp.',
            'image.max' => 'Ukuran gambar maksimal 2 MB.',
        ]);

        $imagePath = $service->image;

        if ($request->boolean('remove_image')) {
            $this->deleteServiceImage($service);
            $imagePath = null;
        }

        if ($request->hasFile('image')) {
            $this->deleteServiceImage($service);
            $imagePath = $this->storeServiceImage($request);
        }

        $service->update([
            'name' => $validated['name'],
            'slug' => $this->generateUniqueSlug($validated['name'], $service->id),
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'duration_minutes' => $validated['duration_minutes'],
            'image' => $imagePath,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $this->deleteServiceImage($service);

        $service->delete();

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Layanan berhasil dihapus.');
    }

    private function storeServiceImage(Request $request): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        return $request->file('image')->store('services', 'public');
    }

    private function deleteServiceImage(Service $service): void
    {
        if ($service->image && Storage::disk('public')->exists($service->image)) {
            Storage::disk('public')->delete($service->image);
        }
    }

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (
            Service::where('slug', $slug)
                ->when($ignoreId, function ($query) use ($ignoreId) {
                    $query->where('id', '!=', $ignoreId);
                })
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}