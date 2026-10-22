<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Admin Smart City</title>

    <!-- Bootstrap 5 & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: #0b132b;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }

        /* Warna kartu login disamakan dengan background abu-abu dashboard */
        .login-card {
            background: #e2e8f0;
            border: 1px solid #cbd5e1;
            border-radius: 24px;
            padding: 40px 35px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
        }

        .header-icon {
            width: 64px;
            height: 64px;
            background-color: #0b132b;
            color: #ffffff;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin: 0 auto 20px auto;
        }

        .login-title {
            color: #0f172a;
            font-weight: 700;
            font-size: 26px;
            text-align: center;
            margin-bottom: 6px;
        }

        .login-subtitle {
            color: #475569;
            font-size: 14px;
            text-align: center;
            margin-bottom: 28px;
        }

        .form-label {
            font-weight: 700;
            color: #1e293b;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .input-group {
            border: 1px solid #94a3b8;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.2s ease-in-out;
            background-color: #ffffff;
        }

        .input-group-text {
            background-color: #f1f5f9;
            border: none;
            color: #334155;
            padding-left: 16px;
            padding-right: 12px;
            font-size: 16px;
        }

        .toggle-password {
            cursor: pointer;
            padding-right: 16px;
            padding-left: 12px;
        }

        .toggle-password:hover {
            color: #1d4ed8;
        }

        .form-control {
            background-color: #ffffff;
            border: none;
            padding: 12px 14px;
            font-size: 14px;
            color: #0f172a;
            box-shadow: none !important;
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        .form-control:focus {
            background-color: #ffffff;
            color: #0f172a;
        }

        /* Efek Ring Fokus */
        .input-group:focus-within {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
        }

        .btn-submit {
            background-color: #0b132b;
            border: none;
            border-radius: 12px;
            color: #ffffff;
            font-weight: 700;
            font-size: 15px;
            padding: 12px;
            width: 100%;
            margin-top: 24px;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }

        .btn-submit:hover {
            background-color: #1e3a8a;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>

<div class="login-card">
   <!-- Logo Sendiri -->
    <div class="text-center mb-4">
        <img src="{{ asset('image/logo_smartcity_rm.png') }}" alt="Logo Smart City" style="width: 75px; height: 75px; object-fit: contain;">
    </div>

    <!-- Judul & Subjudul -->
    <h1 class="login-title">SiSity</h1>
    <p class="login-subtitle">Silakan masuk untuk memantau sistem</p>

    <!-- Form Login Laravel -->
    <form action="{{ route('login') }}" method="POST">
        @csrf

        <!-- Username -->
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fas fa-user"></i>
                </span>
                <input type="text" 
                       id="username" 
                       name="username" 
                       class="form-control @error('username') is-invalid @enderror" 
                       placeholder="Masukkan username" 
                       value="{{ old('username') }}" 
                       required 
                       autofocus>
            </div>
            @error('username')
                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password + Ikon Mata -->
        <div class="mb-2">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fas fa-lock"></i>
                </span>
                <input type="password" 
                       id="password" 
                       name="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       placeholder="Masukkan password" 
                       required>
                <span class="input-group-text toggle-password" onclick="togglePasswordVisibility()">
                    <i class="fas fa-eye" id="eyeIcon"></i>
                </span>
            </div>
            @error('password')
                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
            @enderror
        </div>

        <!-- Tombol Submit -->
        <button type="submit" class="btn btn-submit">
            Masuk Dashboard
        </button>
    </form>
</div>

<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }
</script>

</body>
</html>