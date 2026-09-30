@extends('layouts.app')

@section('content')
<section class="students-page">
    <div class="students-toolbar">
        <div class="page-heading">
            <h1>Students</h1>
            <p class="student-total">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2M10 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm8-7.5a4 4 0 0 1 0 7.75M20 21v-2a4 4 0 0 0-3-3.87" /></svg>
                <span>Total:</span> {{ $users->total() }}
            </p>
        </div>

        <div class="toolbar-actions">
            <form class="search-form" action="{{ route('user.index') }}" method="GET" role="search">
                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7" /><path d="m20 20-4-4" /></svg>
                <input type="search" name="search" value="{{ $search }}" placeholder="Search For Students, NPM, Class, ID" aria-label="Search students">
            </form>

            <a class="toolbar-button export-button" href="{{ route('user.export') }}">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3v12m0 0 4-4m-4 4-4-4M5 17v3h14v-3" /></svg>
                <span>Export CSV</span>
            </a>

            <button class="toolbar-button add-button" type="button" data-bs-toggle="modal" data-bs-target="#studentModal">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14M5 12h14" /></svg>
                <span>Add Student</span>
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="success-message" role="status">{{ session('success') }}</div>
    @endif

    <div class="students-table-wrap">
        <table class="students-table">
            <thead>
                <tr>
                    <th scope="col" class="id-column">ID</th>
                    <th scope="col" class="name-column">STUDENTS</th>
                    <th scope="col" class="npm-column">NPM</th>
                    <th scope="col" class="class-column">CLASS</th>
                    <th scope="col" class="actions-column">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td class="id-column">{{ $user->id }}</td>
                        <td class="name-column">{{ $user->nama }}</td>
                        <td class="npm-column">{{ $user->npm }}</td>
                        <td class="class-column">{{ $user->kelas?->nama_kelas }}</td>
                        <td class="actions-column">
                            <div class="row-actions">
                                <button class="icon-button edit-button" type="button" aria-label="Edit {{ $user->nama }}" title="Edit student"
                                    data-bs-toggle="modal" data-bs-target="#studentModal" data-mode="edit"
                                    data-id="{{ $user->id }}" data-name="{{ $user->nama }}" data-npm="{{ $user->npm }}"
                                    data-class="{{ $user->kelas_id }}" data-action="{{ route('user.update', $user->id) }}">
                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 20h9M16.5 3.5a2.12 2.12 0 0 1 3 3L9 17l-4 1 1-4Z" /><path d="M14 5.5 18.5 10" /></svg>
                                </button>
                                <form action="{{ route('user.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Delete this student?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="icon-button delete-button" type="submit" aria-label="Delete {{ $user->nama }}" title="Delete student">
                                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6h18M8 6V4h8v2m3 0-1 14H6L5 6m4 4v6m6-6v6" /></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="empty-row">
                        <td colspan="5">No students found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-bar">
        <span>
            @if ($users->total() > 0)
                {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }}
            @else
                0 students
            @endif
        </span>
        <nav class="page-navigation" aria-label="Student pages">
            @if ($users->onFirstPage())
                <span class="page-link-disabled">‹</span>
            @else
                <a href="{{ $users->previousPageUrl() }}" aria-label="Previous page">‹</a>
            @endif
            <span>Page {{ $users->currentPage() }} of {{ max(1, $users->lastPage()) }}</span>
            @if ($users->hasMorePages())
                <a href="{{ $users->nextPageUrl() }}" aria-label="Next page">›</a>
            @else
                <span class="page-link-disabled">›</span>
            @endif
        </nav>
    </div>
</section>

<div class="modal fade student-modal" id="studentModal" tabindex="-1" aria-labelledby="studentModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="studentForm" action="{{ route('user.store') }}" method="POST" data-store-action="{{ route('user.store') }}">
                @csrf
                <input type="hidden" name="student_id" id="studentId" value="{{ old('student_id') }}">
                <div class="modal-body">
                    <h2 id="studentModalTitle">Add Student</h2>

                    <div class="student-field">
                        <label for="studentName">NAME</label>
                        <input id="studentName" name="nama" type="text" value="{{ old('nama') }}" autocomplete="name" required>
                        @error('nama') <span class="field-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="student-field">
                        <label for="studentNpm">NPM</label>
                        <input id="studentNpm" name="npm" type="text" value="{{ old('npm') }}" inputmode="numeric" required>
                        @error('npm') <span class="field-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="student-field class-field">
                        <label for="studentClass">CLASS</label>
                        <select id="studentClass" name="kelas_id" required>
                            <option value="" disabled @selected(!old('kelas_id'))>Select class</option>
                            @foreach ($kelas as $class)
                                <option value="{{ $class->id }}" @selected((string) old('kelas_id') === (string) $class->id)>{{ $class->nama_kelas }}</option>
                            @endforeach
                        </select>
                        @error('kelas_id') <span class="field-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="modal-actions">
                        <button class="modal-cancel" type="button" data-bs-dismiss="modal">CANCEL</button>
                        <button class="modal-submit" id="studentSubmit" type="submit">ADD STUDENT</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const studentModal = document.getElementById('studentModal');
    const studentForm = document.getElementById('studentForm');
    const methodField = document.createElement('input');
    methodField.type = 'hidden';
    methodField.name = '_method';

    studentModal.addEventListener('show.bs.modal', (event) => {
        const trigger = event.relatedTarget;
        const isEditing = trigger?.dataset.mode === 'edit';

        studentForm.action = isEditing ? trigger.dataset.action : studentForm.dataset.storeAction;
        document.getElementById('studentModalTitle').textContent = isEditing ? 'Edit Student' : 'Add Student';
        document.getElementById('studentSubmit').textContent = isEditing ? 'SAVE CHANGES' : 'ADD STUDENT';
        document.getElementById('studentName').value = isEditing ? trigger.dataset.name : '';
        document.getElementById('studentNpm').value = isEditing ? trigger.dataset.npm : '';
        document.getElementById('studentClass').value = isEditing ? trigger.dataset.class : '';
        document.getElementById('studentId').value = isEditing ? trigger.dataset.id : '';

        if (isEditing) {
            methodField.value = 'PUT';
            studentForm.append(methodField);
        } else {
            methodField.remove();
        }
    });

    @if ($errors->any() || request()->boolean('add'))
        bootstrap.Modal.getOrCreateInstance(studentModal).show();
    @endif
</script>
@endpush

@endsection
