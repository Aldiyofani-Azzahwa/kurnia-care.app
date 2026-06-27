<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Services\SupabaseStorageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

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

    public function store(
        Request $request,
        SupabaseStorageService $storage
    ): RedirectResponse {
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

        $imageUrl = null;

        try {
            if ($request->hasFile('image')) {
                $imageUrl = $this->storeServiceImage($request, $storage);
            }

            DB::transaction(function () use ($validated, $request, $imageUrl) {
                Service::create([
                    'name' => $validated['name'],
                    'slug' => $this->generateUniqueSlug($validated['name']),
                    'description' => $validated['description'] ?? null,
                    'price' => $validated['price'],
                    'duration_minutes' => $validated['duration_minutes'],
                    'image' => $imageUrl,
                    'is_active' => $request->boolean('is_active'),
                ]);
            });

            return redirect()
                ->route('admin.services.index')
                ->with('success', 'Layanan berhasil ditambahkan.');
        } catch (Throwable $e) {
            $this->deleteServiceImage($imageUrl, $storage);

            report($e);

            return back()
                ->withInput()
                ->with('error', 'ERROR: ' . $e->getMessage());
        }
    }

    public function edit(Service $service): View
    {
        return view('admin.services.edit', [
            'service' => $service,
        ]);
    }

    public function update(
        Request $request,
        Service $service,
        SupabaseStorageService $storage
    ): RedirectResponse {
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

        $oldImagePath = $service->image;
        $newImageUrl = null;
        $finalImagePath = $oldImagePath;

        try {
            if ($request->hasFile('image')) {
                $newImageUrl = $this->storeServiceImage($request, $storage);
                $finalImagePath = $newImageUrl;
            } elseif ($request->boolean('remove_image')) {
                $finalImagePath = null;
            }

            DB::transaction(function () use ($service, $validated, $request, $finalImagePath) {
                $lockedService = Service::whereKey($service->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $lockedService->update([
                    'name' => $validated['name'],
                    'slug' => $this->generateUniqueSlug($validated['name'], $lockedService->id),
                    'description' => $validated['description'] ?? null,
                    'price' => $validated['price'],
                    'duration_minutes' => $validated['duration_minutes'],
                    'image' => $finalImagePath,
                    'is_active' => $request->boolean('is_active'),
                ]);
            });

            if ($oldImagePath && $oldImagePath !== $finalImagePath) {
                $this->deleteServiceImage($oldImagePath, $storage);
            }

            return redirect()
                ->route('admin.services.index')
                ->with('success', 'Layanan berhasil diperbarui.');
        } catch (Throwable $e) {
            $this->deleteServiceImage($newImageUrl, $storage);

            report($e);

            return back()
                ->withInput()
                ->with('error', 'ERROR: ' . $e->getMessage());
        }
    }

    public function destroy(
        Service $service,
        SupabaseStorageService $storage
    ): RedirectResponse {
        if ($service->appointments()->exists()) {
            return back()->with(
                'error',
                'Layanan tidak dapat dihapus karena sudah dipakai appointment. Nonaktifkan layanan jika tidak ingin ditampilkan.'
            );
        }

        $oldImagePath = $service->image;

        try {
            DB::transaction(function () use ($service) {
                $lockedService = Service::whereKey($service->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($lockedService->appointments()->exists()) {
                    throw new \RuntimeException(
                        'Layanan tidak dapat dihapus karena sudah dipakai appointment. Nonaktifkan layanan jika tidak ingin ditampilkan.'
                    );
                }

                $lockedService->delete();
            });

            $this->deleteServiceImage($oldImagePath, $storage);

            return redirect()
                ->route('admin.services.index')
                ->with('success', 'Layanan berhasil dihapus.');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        } catch (Throwable $e) {
            report($e);

            return back()->with('error', 'ERROR: ' . $e->getMessage());
        }
    }

    private function storeServiceImage(Request $request, SupabaseStorageService $storage): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        return $storage->upload($request->file('image'), 'services');
    }

    private function deleteServiceImage(?string $imagePath, SupabaseStorageService $storage): void
    {
        if (! $imagePath) {
            return;
        }

        if (str_starts_with($imagePath, 'http://') || str_starts_with($imagePath, 'https://')) {
            $storage->delete($imagePath);
            return;
        }

        if (Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }
    }

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);

        if ($baseSlug === '') {
            $baseSlug = 'layanan';
        }

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