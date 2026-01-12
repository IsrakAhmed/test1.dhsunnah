@extends('layouts.admin')

@section('title', 'ID Card Create')

@section('content')
<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">ID Card Generator</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.idcard.generate') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- School Selection -->
                    <div class="mb-4">
                        <label for="user_id" class="form-label fw-bold">স্কুল / ইউজার নির্বাচন করুন</label>
                        <select name="user_id" id="user_id" class="form-select form-select-lg" required>
                            <option value="">-- স্কুল নির্বাচন করুন --</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                            @endforeach
                        </select>
                        @error('user_id')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Class Selection -->
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="class" class="form-label fw-bold">ক্লাস নির্বাচন করুন (ঐচ্ছিক)</label>
                            <select name="class" id="class" class="form-select form-select-lg" {{ old('user_id') ? '' : 'disabled' }}>
                                <option value="">-- সকল ক্লাস --</option>
                            </select>
                            <div class="form-text">Leave empty for all classes.</div>
                            @error('class')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-4">
                            <label for="section" class="form-label fw-bold">সেকশন নির্বাচন করুন (ঐচ্ছিক)</label>
                            <select name="section" id="section" class="form-select form-select-lg" disabled>
                                <option value="">-- সকল সেকশন --</option>
                            </select>
                            <div class="form-text">Select a class first to see sections.</div>
                            @error('section')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Custom Background Upload -->
                    <div class="mb-4">
                        <label for="custom_design" class="form-label fw-bold">Custom Background (PNG)</label>
                        <div class="input-group">
                            <input type="file" name="custom_design" id="custom_design" class="form-control" accept="image/png">
                            <button class="btn btn-outline-secondary" type="button" id="previewCustom">Preview</button>
                        </div>
                        <div class="form-text">Upload a 55mm x 87mm PNG, similar to the sample below.</div>
                        <img src="{{ asset('images/idcard-sample.png') }}" alt="Sample design" class="mt-3 rounded" style="max-width: 250px;">
                        @php
                        $previewStudent = collect($sampleStudents)->first();

                        $previewPhotoName = $previewStudent['name'] ?? 'Student';

                        $previewPhoto = !empty($previewStudent['image'])
                        ? asset('storage/' . $previewStudent['image'])
                        : 'https://ui-avatars.com/api/?name=' . urlencode($previewPhotoName) . '&background=cccccc&color=222';

                        $previewName = $previewStudent['name'] ?? 'Student Name';
                        $previewSchool = $previewStudent['user']['name'] ?? 'School Name';

                        $classValue = $previewStudent['class'] ?? 'Class';
                        $sectionValue = $previewStudent['section'] ?? null;
                        $previewClass = trim($classValue . ($sectionValue ? ' - ' . $sectionValue : ''));

                        $previewId = $previewStudent['registration_no'] ?? 'ID0000';
                        $previewRoll = $previewStudent['roll_no'] ?? 'Roll';
                        $previewFather = $previewStudent['father_name'] ?? 'Father Name';
                        $previewMobile = $previewStudent['mobile_no'] ?? '01XXXXXXXXX';
                        $previewBlood = $previewStudent['blood_group'] ?? 'N/A';
                        @endphp

                        <div id="customPreviewWrapper" class="mt-4 d-none">
                            <p class="fw-bold small text-secondary mb-2 text-uppercase">Uploaded Preview</p>
                            <div class="custom-upload-preview">
                                <img id="customPreview" src="" alt="Custom preview" class="custom-upload-preview__bg">
                                <div class="custom-upload-preview__content">
                                    <small>{{ $previewSchool }}</small>
                                    <div class="preview-photo" style="background-image: url('{{ $previewPhoto }}');"></div>
                                    <h6>{{ $previewName }}</h6>
                                    <div class="preview-info"><span>Father:</span> {{ $previewFather }}</div>
                                    <div class="preview-info"><span>Class:</span> {{ $previewClass }}</div>
                                    <div class="preview-info"><span>ID No:</span> {{ $previewId }}</div>
                                    <div class="preview-info"><span>Roll:</span> {{ $previewRoll }}</div>
                                    <div class="preview-info"><span>Mobile:</span> {{ $previewMobile }}</div>
                                    <div class="preview-info"><span>Blood:</span> {{ $previewBlood }}</div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="bi bi-credit-card-2-front me-2"></i>
                            Generate ID Cards
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .design-preview:hover {
        background-color: #f8f9fa;
        transform: scale(1.02);
    }

    .btn-check:checked+label .design-preview {
        background-color: #e7f3ff;
        border-color: #0d6efd !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .custom-upload-preview {
        position: relative;
        width: 220px;
        height: 348px;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .custom-upload-preview__bg {
        width: 100%;
        height: 100%;
        object-fit: contain;
        object-position: center;
        position: absolute;
        top: 0;
        left: 0;
    }

    .custom-upload-preview__content {
        position: absolute;
        inset: 0;
        background: transparent;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        padding: 0;
        text-align: center;
    }

    .custom-upload-preview__content small {
        color: #222;
        font-weight: 600;
        text-shadow: 0 0 4px white, 0 0 8px white;
        margin-top: 38px;
        margin-bottom: 3px;
        display: block;
        font-size: 9px;
        line-height: 1.1;
        max-width: 170px;
        padding: 0 10px;
    }

    .custom-upload-preview__content .preview-photo {
        width: 90px;
        height: 115px;
        border-radius: 4px;
        background: #e0e0e0;
        background-size: cover;
        background-position: center 15%;
        margin-bottom: 6px;
        margin-top: 30px;
        border: 2px solid #d32f2f;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.25);
    }

    .custom-upload-preview__content h6 {
        font-weight: 700;
        margin: 0 0 4px 0;
        color: #d32f2f;
        text-shadow: 0 0 4px white, 0 0 8px white, 1px 1px 2px rgba(255, 255, 255, 0.95);
        font-size: 13px;
        line-height: 1.1;
        max-width: 180px;
    }

    .custom-upload-preview__content .preview-info {
        font-size: 9px;
        width: 175px;
        display: flex;
        justify-content: flex-start;
        gap: 10px;
        margin-top: 1px;
        padding: 1px 0;
        color: #1a1a1a;
        font-weight: 500;
        text-shadow: 0 0 3px white, 0 0 6px white;
        line-height: 1.2;
    }

    .custom-upload-preview__content .preview-info span {
        font-weight: 700;
        color: #000;
        min-width: 50px;
    }

    .custom-upload-preview__signature {
        margin-top: auto;
        margin-bottom: 50px;
        font-size: 8px;
        color: #555;
        font-style: italic;
        text-shadow: 0 0 4px white;
        font-weight: 500;
    }
</style>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dataByUser = @json($dataByUser ?? []);
        const userSelect = document.getElementById('user_id');
        const classSelect = document.getElementById('class');
        const sectionSelect = document.getElementById('section');
        let preservedClass = @json(old('class'));
        let preservedSection = @json(old('section'));
        const customInput = document.getElementById('custom_design');
        const previewBtn = document.getElementById('previewCustom');
        const previewWrapper = document.getElementById('customPreviewWrapper');
        const previewImg = document.getElementById('customPreview');


        const clearSelect = (select, defaultText) => {
            select.innerHTML = '';
            const option = document.createElement('option');
            option.value = '';
            option.textContent = defaultText;
            select.appendChild(option);
            select.disabled = true;
        };

        const populateClasses = (userId) => {
            clearSelect(classSelect, '-- সকল ক্লাস --');
            clearSelect(sectionSelect, '-- সকল সেকশন --');

            const classes = dataByUser[userId] ? Object.keys(dataByUser[userId]) : [];

            if (!userId || classes.length === 0) {
                return;
            }

            classes.forEach(cls => {
                const option = document.createElement('option');
                option.value = cls;
                option.textContent = cls;
                classSelect.appendChild(option);
            });

            classSelect.disabled = false;

            if (preservedClass && classes.includes(preservedClass)) {
                classSelect.value = preservedClass;
                populateSections(userId, preservedClass);
            }
        };

        const populateSections = (userId, className) => {
            clearSelect(sectionSelect, '-- সকল সেকশন --');

            if (!userId || !className) return;

            const sections = dataByUser[userId] && dataByUser[userId][className] ? dataByUser[userId][className] : [];

            if (sections.length === 0) {
                return;
            }

            sections.forEach(sec => {
                const option = document.createElement('option');
                option.value = sec;
                option.textContent = sec;
                sectionSelect.appendChild(option);
            });

            sectionSelect.disabled = false;

            if (preservedSection && sections.includes(preservedSection)) {
                sectionSelect.value = preservedSection;
            }
        };

        if (userSelect.value) {
            populateClasses(userSelect.value);
        }

        userSelect.addEventListener('change', () => {
            preservedClass = '';
            preservedSection = '';
            populateClasses(userSelect.value);
        });

        classSelect.addEventListener('change', () => {
            preservedSection = '';
            populateSections(userSelect.value, classSelect.value);
            updatePreview(classSelect.value);
        });

        const showPreview = () => {
            const file = customInput.files?.[0];
            if (!file) {
                previewWrapper.classList.add('d-none');
                previewImg.src = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = e => {
                previewImg.src = e.target?.result ?? '';
                previewWrapper.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        };

        customInput?.addEventListener('change', showPreview);
        previewBtn?.addEventListener('click', showPreview);

        classSelect.addEventListener('change', () => {
            updatePreview(classSelect.value);
        });
    });


    const sampleStudents = @json($sampleStudents);

    const updatePreview = (className) => {
        let student;

        if (className && sampleStudents[className]) {
            student = sampleStudents[className];
        } else {
            // default to first class (e.g. "One")
            student = Object.values(sampleStudents)[0];
        }

        if (!student) return;

        document.querySelector('.preview-photo').style.backgroundImage =
            `url(${student.image ? '/storage/' + student.image : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(student.name)})`;

        document.querySelector('.custom-upload-preview__content h6').innerText =
            student.name ?? 'Student Name';

        document.querySelector('.custom-upload-preview__content small').innerText =
            student.user?.name ?? 'School Name';

        const infoRows = document.querySelectorAll('.preview-info');

        infoRows[0].lastChild.textContent = student.father_name ?? 'Father Name';
        infoRows[1].lastChild.textContent =
            student.class + (student.section ? ' - ' + student.section : '');
        infoRows[2].lastChild.textContent = student.registration_no ?? 'ID0000';
        infoRows[3].lastChild.textContent = student.roll_no ?? 'Roll';
        infoRows[4].lastChild.textContent = student.mobile_no ?? '01XXXXXXXXX';
        infoRows[5].lastChild.textContent = student.blood_group ?? 'N/A';
    };
</script>
@endsection
