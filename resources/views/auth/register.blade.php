@extends('layouts.auth')

@section('title', 'Register')

@section('content')
    <div class="row justify-content-center">
        <div class="">
            <div class="card auth-card shadow-lg">
                <div class="card-body">
                    <h4 class="text-center mb-2 auth-title">Buat Akun Baru</h4>

                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.process') }}">
                        @csrf

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label">Nama</label>
                                <input type="text" name="name" value="{{ old('name') }}" class="form-control"
                                    required>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control"
                                    required>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Status</label>
                                <select name="role" id="role" class="form-select" required>
                                    <option value="" disabled selected>Pilih Status</option>
                                    <option value="siswa" {{ old('role') === 'siswa' ? 'selected' : '' }}>Siswa</option>
                                    <option value="guru" {{ old('role') === 'guru' ? 'selected' : '' }}>Guru</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6" id="telp-field">
                                <label class="form-label">Nomor Telepon</label>
                                <input type="text" name="nomor_telepon" value="{{ old('nomor_telepon') }}"
                                    class="form-control" required>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>

                            <div id="siswa-fields" class="col-12 d-none">
                                <label class="form-label">Kelas</label>
                                <input type="text" name="kelas" value="{{ old('kelas') }}" class="form-control"
                                    id="kelas-input">
                            </div>

                            <div id="guru-fields" class="col-12 d-none">
                                <label class="form-label">Bidang yang Diajar</label>
                                <input type="text" name="bidang_ajar" value="{{ old('bidang_ajar') }}"
                                    class="form-control" id="bidang-ajar-input">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-auth-primary w-100 mt-4">
                            Daftar
                        </button>

                        <div class="text-center mt-3">
                            <small>Sudah punya akun?
                                <a class="auth-link" href="{{ route('login') }}">Login</a>
                            </small>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const roleSelect = document.getElementById('role');
        const siswaFields = document.getElementById('siswa-fields');
        const guruFields = document.getElementById('guru-fields');

        const kelasInput = document.getElementById('kelas-input');
        const bidangAjarInput = document.getElementById('bidang-ajar-input');

        const toggleFields = () => {
            const role = roleSelect.value;
            siswaFields.classList.toggle('d-none', role !== 'siswa');
            guruFields.classList.toggle('d-none', role !== 'guru');
            kelasInput.required = role === 'siswa';
            bidangAjarInput.required = role === 'guru';
        };

        roleSelect.addEventListener('change', toggleFields);
        toggleFields();
    </script>
@endsection
