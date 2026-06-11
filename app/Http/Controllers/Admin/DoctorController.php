<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DoctorController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $doctors = Doctor::with('user')
            ->withCount(['appointments', 'schedules'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('specialist', 'like', "%{$search}%")
                        ->orWhere('sip_number', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('email', 'like', "%{$search}%");
                        });
                });
            })
            ->when($status !== null && $status !== '', function ($query) use ($status) {
                $query->where('is_active', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalDoctors = Doctor::count();
        $activeDoctors = Doctor::where('is_active', true)->count();
        $inactiveDoctors = Doctor::where('is_active', false)->count();
        $totalAppointments = Doctor::withCount('appointments')->get()->sum('appointments_count');

        return view('admin.doctors.index', compact(
            'doctors',
            'search',
            'status',
            'totalDoctors',
            'activeDoctors',
            'inactiveDoctors',
            'totalAppointments'
        ));
    }

    public function create(): View
    {
        return view('admin.doctors.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->storeRules(), $this->messages());

        $photoPath = null;

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('doctors', 'public');
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'dokter',
            'phone' => $validated['phone'] ?? null,
        ]);

        Doctor::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'specialist' => $validated['specialist'] ?? null,
            'sip_number' => $validated['sip_number'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'photo' => $photoPath,
            'bio' => $validated['bio'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.doctors.index')
            ->with('success', 'Data dokter berhasil ditambahkan dan akun login dokter berhasil dibuat.');
    }

    public function show(Doctor $doctor): View
    {
        $doctor->load([
            'user',
            'appointments.patient',
            'appointments.service',
            'schedules',
        ]);

        return view('admin.doctors.show', compact('doctor'));
    }

    public function edit(Doctor $doctor): View
    {
        $doctor->load('user');

        return view('admin.doctors.edit', compact('doctor'));
    }

    public function update(Request $request, Doctor $doctor): RedirectResponse
    {
        $doctor->load('user');

        $validated = $request->validate($this->updateRules($doctor), $this->messages());

        $photoPath = $doctor->photo;

        if ($request->hasFile('photo')) {
            if ($doctor->photo && Storage::disk('public')->exists($doctor->photo)) {
                Storage::disk('public')->delete($doctor->photo);
            }

            $photoPath = $request->file('photo')->store('doctors', 'public');
        }

        if ($doctor->user) {
            $doctor->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
            ]);

            if (!empty($validated['password'])) {
                $doctor->user->update([
                    'password' => Hash::make($validated['password']),
                ]);
            }
        } else {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password'] ?? 'password'),
                'role' => 'dokter',
                'phone' => $validated['phone'] ?? null,
            ]);

            $doctor->user_id = $user->id;
        }

        $doctor->update([
            'user_id' => $doctor->user_id,
            'name' => $validated['name'],
            'specialist' => $validated['specialist'] ?? null,
            'sip_number' => $validated['sip_number'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'photo' => $photoPath,
            'bio' => $validated['bio'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.doctors.index')
            ->with('success', 'Data dokter berhasil diperbarui.');
    }

    public function destroy(Doctor $doctor): RedirectResponse
    {
        if ($doctor->appointments()->exists()) {
            return back()
                ->with('error', 'Dokter tidak dapat dihapus karena sudah memiliki riwayat appointment.');
        }

        if ($doctor->photo && Storage::disk('public')->exists($doctor->photo)) {
            Storage::disk('public')->delete($doctor->photo);
        }

        $user = $doctor->user;

        $doctor->delete();

        if ($user) {
            $user->delete();
        }

        return redirect()
            ->route('admin.doctors.index')
            ->with('success', 'Data dokter berhasil dihapus.');
    }

    private function storeRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],

            'specialist' => ['nullable', 'string', 'max:150'],
            'sip_number' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:30'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'bio' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    private function updateRules(Doctor $doctor): array
    {
        $userId = $doctor->user_id;

        return [
            'name' => ['required', 'string', 'max:150'],
            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'password' => ['nullable', 'string', 'min:6'],

            'specialist' => ['nullable', 'string', 'max:150'],
            'sip_number' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:30'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'bio' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    private function messages(): array
    {
        return [
            'name.required' => 'Nama dokter wajib diisi.',
            'email.required' => 'Email dokter wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh akun lain.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',

            'photo.image' => 'File foto harus berupa gambar.',
            'photo.mimes' => 'Foto harus berformat JPG, JPEG, atau PNG.',
            'photo.max' => 'Ukuran foto maksimal 2MB.',

            'is_active.required' => 'Status dokter wajib dipilih.',
            'is_active.boolean' => 'Status dokter tidak valid.',
        ];
    }
}