@include('layouts.header')
@auth
    @if (Auth::user()->role === 'admin')
        @include('layouts.admin-sidebar')
    @else
        @include('layouts.sidebar')
    @endif
@else
    @include('layouts.sidebar')
@endauth

<main id="main" class="main">
    <div class="card">
        <div class="card-body pt-3">
            <!-- Display Success/Error Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Bordered Tabs -->
            <ul class="nav nav-tabs nav-tabs-bordered">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit
                        Profile</button>
                </li>

                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change
                        Password</button>
                </li>

            </ul>
            <div class="tab-content pt-2">

                <div class="tab-pane fade show active pt-3" id="profile-edit">

                    <!-- Profile Edit Form -->
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                            <div class="col-md-8 col-lg-9">
                                <input name="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror" id="name"
                                    value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                            <div class="col-md-8 col-lg-9">
                                <input name="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" id="email"
                                    value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="phone" class="col-md-4 col-lg-3 col-form-label">Phone</label>
                            <div class="col-md-8 col-lg-9">
                                <input name="phone" type="text"
                                    class="form-control @error('phone') is-invalid @enderror" id="phone"
                                    value="{{ old('phone', $userDetail->phone ?? '') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="address" class="col-md-4 col-lg-3 col-form-label">Address</label>
                            <div class="col-md-8 col-lg-9">
                                <textarea name="address" class="form-control @error('address') is-invalid @enderror" id="address"
                                    style="height: 100px">{{ old('address', $userDetail->address ?? '') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="birth_date" class="col-md-4 col-lg-3 col-form-label">Birth Date</label>
                            <div class="col-md-8 col-lg-9">
                                <input name="birth_date" type="date"
                                    class="form-control @error('birth_date') is-invalid @enderror" id="birth_date"
                                    value="{{ old('birth_date', $userDetail && $userDetail->birth_date ? $userDetail->birth_date->format('Y-m-d') : '') }}">
                                @error('birth_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="gender" class="col-md-4 col-lg-3 col-form-label">Gender</label>
                            <div class="col-md-8 col-lg-9">
                                <select name="gender" class="form-control @error('gender') is-invalid @enderror"
                                    id="gender">
                                    <option value="">Select Gender</option>
                                    <option value="male"
                                        {{ old('gender', $userDetail->gender ?? '') == 'male' ? 'selected' : '' }}>Male
                                    </option>
                                    <option value="female"
                                        {{ old('gender', $userDetail->gender ?? '') == 'female' ? 'selected' : '' }}>
                                        Female</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="cv_path" class="col-md-4 col-lg-3 col-form-label">CV/Resume *</label>
                            <div class="col-md-8 col-lg-9">
                                <input name="cv_path" type="file"
                                    class="form-control @error('cv_path') is-invalid @enderror" id="cv_path"
                                    accept=".pdf,.doc,.docx">
                                <small class="form-text text-muted">PDF, DOC, DOCX (Max 5MB). Required for job
                                    applications.</small>
                                @if ($userDetail && $userDetail->cv_path)
                                    <div class="mt-2">
                                        <i class="bi bi-file-earmark-text text-success"></i>
                                        <a href="{{ asset('storage/' . $userDetail->cv_path) }}" target="_blank"
                                            class="text-success">
                                            Current CV/Resume
                                        </a>
                                    </div>
                                @else
                                    <div class="mt-2 text-warning">
                                        <i class="bi bi-exclamation-triangle"></i>
                                        No CV/Resume uploaded. You need to upload CV to apply for jobs.
                                    </div>
                                @endif
                                @error('cv_path')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="ktp_path" class="col-md-4 col-lg-3 col-form-label">KTP/ID Card *</label>
                            <div class="col-md-8 col-lg-9">
                                <input name="ktp_path" type="file"
                                    class="form-control @error('ktp_path') is-invalid @enderror" id="ktp_path"
                                    accept=".pdf,.jpg,.jpeg,.png">
                                <small class="form-text text-muted">PDF, JPG, JPEG, PNG (Max 2MB). Required for job
                                    applications.</small>
                                @if ($userDetail && $userDetail->ktp_path)
                                    <div class="mt-2">
                                        <i class="bi bi-file-earmark-image text-success"></i>
                                        <a href="{{ asset('storage/' . $userDetail->ktp_path) }}" target="_blank"
                                            class="text-success">
                                            Current KTP/ID Card
                                        </a>
                                    </div>
                                @else
                                    <div class="mt-2 text-warning">
                                        <i class="bi bi-exclamation-triangle"></i>
                                        No KTP/ID Card uploaded. You need to upload KTP to apply for jobs.
                                    </div>
                                @endif
                                @error('ktp_path')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form><!-- End Profile Edit Form -->

                </div>

                <div class="tab-pane fade pt-3" id="profile-change-password">
                    <!-- Change Password Form -->
                    <form method="POST" action="{{ route('password.change') }}">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <label for="current_password" class="col-md-4 col-lg-3 col-form-label">Current
                                Password</label>
                            <div class="col-md-8 col-lg-9">
                                <input name="current_password" type="password"
                                    class="form-control @error('current_password') is-invalid @enderror"
                                    id="current_password" required>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="new_password" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                            <div class="col-md-8 col-lg-9">
                                <input name="new_password" type="password"
                                    class="form-control @error('new_password') is-invalid @enderror" id="new_password"
                                    required>
                                @error('new_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Password must be at least 8 characters long.</div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="new_password_confirmation" class="col-md-4 col-lg-3 col-form-label">Confirm
                                New Password</label>
                            <div class="col-md-8 col-lg-9">
                                <input name="new_password_confirmation" type="password" class="form-control"
                                    id="new_password_confirmation" required>
                                <div class="form-text">Please confirm your new password.</div>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Change Password</button>
                        </div>
                    </form><!-- End Change Password Form -->

                </div>

            </div><!-- End Bordered Tabs -->

        </div>
    </div>
</main>

@include('layouts.foot')
