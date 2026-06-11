@extends('layouts.doctor')

@section('title', 'Catatan Medis')

@section('content')

    <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">

        <div class="mb-6">
            <h3 class="text-lg font-semibold text-emerald-700">
                Catatan Medis
            </h3>

            <p class="text-sm text-gray-500">
                Daftar catatan tindakan yang telah dibuat oleh dokter.
            </p>
        </div>

        @if ($appointments->count() > 0)

            <div class="space-y-4">
                @foreach ($appointments as $appointment)
                    <div class="rounded-xl border border-gray-200 bg-white shadow-sm p-4 md:p-5">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                            <div>
                                <h4 class="font-bold text-emerald-700">
                                    {{ $appointment->patient->child_name ?? '-' }}
                                </h4>

                                <p class="text-sm text-gray-500">
                                    {{ $appointment->service->name ?? '-' }} |
                                    {{ $appointment->appointment_date ? $appointment->appointment_date->format('d-m-Y') : '-' }}
                                </p>
                            </div>

                            <a href="{{ route('doctor.appointments.show', $appointment) }}"
                                class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 text-center">
                                Detail
                            </a>
                        </div>

                        <div class="mt-4 space-y-3">
                            @foreach ($appointment->medicalNotes as $note)
                                <div class="rounded-lg bg-gray-50 border border-gray-200 p-4">
                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                                        <p class="text-sm text-gray-500">
                                            Status:
                                            <span class="font-semibold text-emerald-700">
                                                {{ str_replace('_', ' ', ucfirst($note->action_status)) }}
                                            </span>
                                        </p>

                                        <p class="text-xs text-gray-400">
                                            {{ $note->created_at->format('d-m-Y H:i') }}
                                        </p>
                                    </div>

                                    <p class="mt-2 text-gray-700">
                                        {{ $note->note }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $appointments->links() }}
            </div>

        @else

            <div class="text-center py-12">
                <p class="text-gray-500">
                    Belum ada catatan medis.
                </p>
            </div>

        @endif

    </div>

@endsection