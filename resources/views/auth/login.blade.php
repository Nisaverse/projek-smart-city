<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - SmartCity Tegal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #F8FAFC;
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 24px 24px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #0F172A;
        }
        .login-wrapper {
            display: flex;
            background: #FFFFFF;
            border-radius: 24px;
            overflow: hidden;
            width: 100%;
            max-width: 900px;
            box-shadow: 0 20px 40px rgba(30, 58, 138, 0.08);
            border: 1px solid rgba(154, 177, 122, 0.3);
        }
        
        /* SISI KIRI: Sekarang Background Putih, Logo Besar di Tengah */
        .login-info-side {
            flex: 1;
            background: #FFFFFF;
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            border-right: 1px solid #f1f5f9;
        }
        .large-logo-img {
            width: 160px;
            height: 160px;
            object-fit: contain;
            margin-bottom: 20px;
            filter: drop-shadow(0 10px 20px rgba(30, 58, 138, 0.18));
        }
        .login-info-side h2 {
            font-weight: 700;
            color: #1E3A8A;
            font-size: 1.8rem;
            margin-bottom: 10px;
        }
        .login-info-side p {
            color: #64748b;
            font-size: 0.9rem;
            line-height: 1.5;
            max-width: 300px;
        }

        /* SISI KANAN: Sekarang Background Biru Gelap untuk Form Login */
        .login-form-side {
            flex: 1.1;
            background: linear-gradient(135deg, #1E3A8A 0%, #0F172A 100%);
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: #FFFFFF;
            position: relative;
        }
        .form-label {
            color: #F8FAFC !important;
            font-weight: 600;
            font-size: 0.85rem;
        }
        .form-control {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid #334155;
            color: #fff;
            padding: 11px 15px;
            border-radius: 0 10px 10px 0;
            font-size: 0.95rem;
        }
        .form-control:focus {
            background: rgba(15, 23, 42, 0.9);
            border-color: #2563EB;
            color: #fff;
            box-shadow: none;
        }
        .form-control::placeholder {
            color: #64748b;
        }
        .input-group {
            border: 1px solid #334155;
            border-radius: 10px;
            overflow: hidden;
            background: rgba(15, 23, 42, 0.6);
        }
        .input-group:focus-within {
            border-color: #2563EB;
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
        }
        .input-group-text {
            background: transparent;
            border: none;
            color: #60a5fa;
        }
        /* Tombol Ikon Mata */
        .toggle-password {
            background: transparent;
            border: none;
            color: #94a3b8;
            padding: 0 15px;
            cursor: pointer;
            transition: color 0.2s;
        }
        .toggle-password:hover {
            color: #fff;
        }
        .btn-login {
            background: linear-gradient(135deg, #2563EB 0%, #1d4ed8 100%);
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            width: 100%;
            color: #fff;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #1d4ed8 100%, #1e40af 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(37, 99, 235, 0.4);
        }
        .badge-tegal {
            background-color: #9AB17A;
            color: #0F172A;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            display: inline-block;
            margin-bottom: 12px;
        }

        @media (max-width: 768px) {
            .login-wrapper {
                flex-direction: column;
                max-width: 400px;
                margin: 20px;
            }
            .login-info-side, .login-form-side {
                padding: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <!-- SISI KIRI: Background Putih, Logo Besar di Tengah -->
        <div class="login-info-side">
            <span class="badge-tegal"><i class="fas fa-map-marker-alt"></i> Kota Tegal</span>
            
            <!-- Logo Diperbesar dan Posisinya di Tengah -->
            <img src="{{ asset('image/logo_smartcity_rm.png') }}" alt="Logo Smart City" class="large-logo-img">
            
            <h2>Smart City</h2>
            <p>Sistem integrasi pemantauan fasilitas publik dan infrastruktur cerdas Kota Tegal secara <i>real-time</i>.</p>
        </div>

        <!-- SISI KANAN: Background Biru, Tempat Form Login -->
        <div class="login-form-side">
            <div class="mb-4">
                <h4 style="font-weight: 700; color: #FFFFFF; margin-bottom: 5px;">Masuk Akun</h4>
                <p class="text-muted" style="font-size: 0.85rem; color: #94a3b8 !important;">Silakan masukkan kredensial Anda untuk akses sistem.</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success py-2 bg-success text-white border-0">
                    <small><i class="fas fa-check-circle"></i> {{ session('success') }}</small>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger py-2 bg-danger text-white border-0">
                    <small><i class="fas fa-exclamation-circle"></i> {{ $errors->first('username') }}</small>
                </div>
            @endif

            <form method="POST" action="/login">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" name="username" class="form-control border-start-0" placeholder="Masukkan username" value="{{ old('username') }}" required autofocus style="border-radius: 0 10px 10px 0;">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password" id="password" class="form-control border-start-0 border-end-0" placeholder="Masukkan password" required style="border-radius: 0;">
                        <!-- Tombol Ikon Mata -->
                        <button type="button" class="toggle-password" id="togglePassword">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-login">
                    Masuk ke Dashboard <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </form>

            <div class="text-center mt-4">
                <small class="text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px; color: #94a3b8 !important;">
                    &copy; 2026 Copyright by DOKTORTJ DIGITAL INSTITUTE
                </small>
            </div>
        </div>
    </div>

    <!-- Script JavaScript untuk fungsi ikon mata -->
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            if (type === 'text') {
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });
    </script>
</body>
</html>