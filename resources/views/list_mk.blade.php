@extends('layouts.app')

@section('content')
<section class="students-page">
    <div class="students-toolbar">
        <div class="page-heading">
            <h1>Subject List</h1>
        </div>

        <div class="toolbar-actions">
            <form class="search-form" action="{{ route('matakuliah.index') }}" method="GET" role="search">
                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7" /><path d="m20 20-4-4" /></svg>
                <input type="search" name="search" value="{{ $search }}" placeholder="Search For Mata Kuliah" aria-label="Search subjects">
            </form>

            <button class="toolbar-button add-button" type="button" data-bs-toggle="modal" data-bs-target="#subjectModal">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14M5 12h14" /></svg>
                <span>Add Subject</span>
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="success-message" role="status">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="error-message" role="alert">{{ $errors->first() }}</div>
    @endif

    <div class="students-table-wrap">
        <table class="students-table subjects-table">
            <thead>
                <tr>
                    <th scope="col" class="subject-id-column">ID</th>
                    <th scope="col" class="subject-name-column">SUBJECT NAME</th>
                    <th scope="col" class="subject-sks-column">SKS</th>
                    <th scope="col" class="actions-column">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($mks as $mk)
                    <tr>
                        <td class="subject-id-column" title="{{ $mk->id }}">{{ \Illuminate\Support\Str::limit($mk->id, 8, '') }}</td>
                        <td class="subject-name-column">{{ $mk->nama_mk }}</td>
                        <td class="subject-sks-column">{{ $mk->sks }}</td>
                        <td class="actions-column">
                            <div class="row-actions">
                                <button class="icon-button edit-button" type="button" aria-label="Edit {{ $mk->nama_mk }}" title="Edit subject"
                                    data-bs-toggle="modal" data-bs-target="#subjectModal" data-mode="edit"
                                    data-id="{{ $mk->id }}" data-name="{{ $mk->nama_mk }}" data-sks="{{ $mk->sks }}"
                                    data-action="{{ route('matakuliah.update', $mk->id) }}">
                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 20h9M16.5 3.5a2.12 2.12 0 0 1 3 3L9 17l-4 1 1-4Z" /><path d="M14 5.5 18.5 10" /></svg>
                                </button>
                                <form action="{{ route('matakuliah.destroy', $mk->id) }}" method="POST" onsubmit="return confirm('Delete this subject?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="icon-button delete-button" type="submit" aria-label="Delete {{ $mk->nama_mk }}" title="Delete subject">
                                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6h18M8 6V4h8v2m3 0-1 14H6L5 6m4 4v6m6-6v6" /></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="empty-row">
                        <td colspan="4">No subjects found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-bar">
        <span>
            @if ($mks->total() > 0)
                {{ $mks->firstItem() }} to {{ $mks->lastItem() }} of {{ $mks->total() }}
            @else
                0 subjects
            @endif
        </span>
        <nav class="page-navigation" aria-label="Subject pages">
            @if ($mks->onFirstPage())
                <span class="page-link-disabled">‹</span>
            @else
                <a href="{{ $mks->previousPageUrl() }}" aria-label="Previous page">‹</a>
            @endif
            <span>Page {{ $mks->currentPage() }} of {{ max(1, $mks->lastPage()) }}</span>
            @if ($mks->hasMorePages())
                <a href="{{ $mks->nextPageUrl() }}" aria-label="Next page">›</a>
            @else
                <span class="page-link-disabled">›</span>
            @endif
        </nav>
    </div>
</section>

<div class="modal fade subject-modal" id="subjectModal" tabindex="-1" aria-labelledby="subjectModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="subjectForm" action="{{ old('mk_id') ? route('matakuliah.update', old('mk_id')) : route('matakuliah.store') }}" method="POST" data-store-action="{{ route('matakuliah.store') }}">
                @csrf
                <input type="hidden" name="mk_id" id="subjectId" value="{{ old('mk_id') }}">
                <div class="modal-body">
                    <h2 id="subjectModalTitle">{{ old('mk_id') ? 'Edit Subject' : 'Add Subject' }}</h2>

                    <div class="subject-field">
                        <label for="subjectName">SUBJECT NAME</label>
                        <input id="subjectName" name="nama_mk" type="text" value="{{ old('nama_mk') }}" required>
                        @error('nama_mk') <span class="field-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="subject-field">
                        <label for="subjectSks">SKS</label>
                        <input class="sks-input" id="subjectSks" name="sks" type="number" min="1" value="{{ old('sks') }}" required>
                        @error('sks') <span class="field-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="modal-actions">
                        <button class="modal-cancel" type="button" data-bs-dismiss="modal">CANCEL</button>
                        <button class="modal-submit" id="subjectSubmit" type="submit">{{ old('mk_id') ? 'SAVE CHANGES' : 'ADD SUBJECT' }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const subjectModal = document.getElementById('subjectModal');
    const subjectForm = document.getElementById('subjectForm');
    const subjectMethod = document.createElement('input');
    subjectMethod.type = 'hidden';
    subjectMethod.name = '_method';

    subjectModal.addEventListener('show.bs.modal', (event) => {
        const trigger = event.relatedTarget;
        const isEditing = trigger?.dataset.mode === 'edit';

        subjectForm.action = isEditing ? trigger.dataset.action : subjectForm.dataset.storeAction;
        document.getElementById('subjectModalTitle').textContent = isEditing ? 'Edit Subject' : 'Add Subject';
        document.getElementById('subjectSubmit').textContent = isEditing ? 'SAVE CHANGES' : 'ADD SUBJECT';
        document.getElementById('subjectName').value = isEditing ? trigger.dataset.name : '';
        document.getElementById('subjectSks').value = isEditing ? trigger.dataset.sks : '';
        document.getElementById('subjectId').value = isEditing ? trigger.dataset.id : '';

        if (isEditing) {
            subjectMethod.value = 'PUT';
            subjectForm.append(subjectMethod);
        } else {
            subjectMethod.remove();
        }
    });

    @if (old('mk_id'))
        subjectMethod.value = 'PUT';
        subjectForm.append(subjectMethod);
    @endif

    @if ($errors->any() || request()->boolean('add'))
        bootstrap.Modal.getOrCreateInstance(subjectModal).show();
    @endif
</script>
@endpush
@endsection
