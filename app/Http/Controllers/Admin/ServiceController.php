<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class ServiceController extends Controller
{
    /**
     * Menampilkan daftar layanan.
     */
    public function index(): View
    {
        $services = Service::latest()
            ->paginate(10);

        return view('admin.services.index', [
            'services' => $services,
        ]);
    }

    /**
     * Menampilkan form tambah layanan.
     */
    public function create(): View
    {
        return view('admin.services.create');
    }

    /**
     * Menyimpan layanan baru.
     */
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

        $imagePath = null;

        try {
            if ($request->hasFile('image')) {
                $imagePath = $this->storeServiceImage($request);
            }

            DB::transaction(function () use ($validated, $request, $imagePath) {
                Service::create([
                    'name' => $validated['name'],
                    'slug' => $this->generateUniqueSlug($validated['name']),
                    'description' => $validated['description'] ?? null,
                    'price' => $validated['price'],
                    'duration_minutes' => $validated['duration_minutes'],
                    'image' => $imagePath,
                    'is_active' => $request->boolean('is_active'),
                ]);
            });

            return redirect()
                ->route('admin.services.index')
                ->with('success', 'Layanan berhasil ditambahkan.');

        } catch (Throwable $e) {
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            report($e);

            return back()
                ->withInput()
                ->with('error', 'Layanan gagal ditambahkan. Silakan coba lagi.');
        }
    }

    /**
     * Menampilkan form edit layanan.
     */
    public function edit(Service $service): View
    {
        return view('admin.services.edit', [
            'service' => $service,
        ]);
    }

    /**
     * Memperbarui layanan.
     */
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

        $oldImagePath = $service->image;
        $newImagePath = null;
        $finalImagePath = $oldImagePath;

        try {
            if ($request->hasFile('image')) {
                $newImagePath = $this->storeServiceImage($request);
                $finalImagePath = $newImagePath;
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

            if (
                $oldImagePath &&
                $oldImagePath !== $finalImagePath &&
                Storage::disk('public')->exists($oldImagePath)
            ) {
                Storage::disk('public')->delete($oldImagePath);
            }

            return redirect()
                ->route('admin.services.index')
                ->with('success', 'Layanan berhasil diperbarui.');

        } catch (Throwable $e) {
            if ($newImagePath && Storage::disk('public')->exists($newImagePath)) {
                Storage::disk('public')->delete($newImagePath);
            }

            report($e);

            return back()
                ->withInput()
                ->with('error', 'Layanan gagal diperbarui. Silakan coba lagi.');
        }
    }

    /**
     * Menghapus layanan.
     */
    public function destroy(Service $service): RedirectResponse
    {
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

            if ($oldImagePath && Storage::disk('public')->exists($oldImagePath)) {
                Storage::disk('public')->delete($oldImagePath);
            }

            return redirect()
                ->route('admin.services.index')
                ->with('success', 'Layanan berhasil dihapus.');

        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());

        } catch (Throwable $e) {
            report($e);

            return back()->with('error', 'Layanan gagal dihapus. Silakan coba lagi.');
        }
    }

    /**
     * Simpan gambar layanan ke storage public.
     */
    private function storeServiceImage(Request $request): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        return $request->file('image')->store('services', 'public');
    }

    /**
     * Membuat slug unik untuk layanan.
     */
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