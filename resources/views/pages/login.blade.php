<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - PAUD ATTHOHIRIYYAH</title>
  <link rel="stylesheet" href="{{ asset('css/main.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Quicksand:wght@500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    :root {
      --login-bg: #f8fafc;
      --login-card: #ffffff;
    }

    body {
      background: var(--login-bg);
      font-family: 'Nunito', sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      position: relative;
    }

    .back-home {
      position: absolute;
      top: 30px;
      left: 30px;
      z-index: 100;
    }

    .back-home a {
      display: flex;
      align-items: center;
      gap: 10px;
      text-decoration: none;
      color: var(--primary);
      font-weight: 800;
      font-size: 0.9rem;
      transition: all 0.3s ease;
      background: white;
      padding: 10px 20px;
      border-radius: 50px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    .back-home a:hover {
      transform: translateX(-5px);
      box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    }

    .login-container {
      max-width: 900px;
      width: 100%;
      background: var(--login-card);
      border-radius: 40px;
      overflow: hidden;
      display: flex;
      box-shadow: 0 40px 100px rgba(3, 79, 32, 0.1);
      border: 1px solid rgba(0,0,0,0.05);
    }

    .login-visual {
      flex: 1;
      background: var(--primary);
      padding: 4rem;
      color: white;
      display: flex;
      flex-direction: column;
      justify-content: center;
      position: relative;
      overflow: hidden;
    }

    .login-visual::before {
      content: '';
      position: absolute;
      width: 400px;
      height: 400px;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 50%;
      top: -100px;
      right: -100px;
    }

    .login-visual h2 {
      color: white;
      font-size: 2.4rem;
      font-weight: 900;
      line-height: 1.3;
      margin-bottom: 1.5rem;
      position: relative;
    }

    .login-visual p {
      font-size: 1.1rem;
      opacity: 0.95;
      line-height: 1.8;
      max-width: 100%;
    }

    .role-badge {
      display: inline-block;
      padding: 10px 20px;
      background: var(--secondary);
      color: var(--primary);
      border-radius: 50px;
      font-weight: 800;
      font-size: 0.8rem;
      margin-bottom: 2rem;
      text-transform: uppercase;
      letter-spacing: 2px;
    }

    .login-form-side {
      flex: 1.2;
      padding: 5rem;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .login-header {
      margin-bottom: 3.5rem;
    }

    .login-header h1 {
      font-size: 2.5rem;
      color: var(--primary);
      font-weight: 800;
      margin-bottom: 0.8rem;
    }

    .login-header p {
      color: var(--text-muted);
      font-size: 1.1rem;
      font-weight: 600;
    }

    .form-group {
      margin-bottom: 1.8rem;
    }

    .form-group label {
      display: block;
      margin-bottom: 0.8rem;
      font-weight: 800;
      color: var(--primary);
      font-size: 0.9rem;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .input-wrapper {
      position: relative;
    }

    .input-wrapper i {
      position: absolute;
      left: 20px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--primary);
      opacity: 0.5;
    }

    .form-group input {
      width: 100%;
      padding: 1.2rem 1.2rem 1.2rem 3.5rem;
      border: 2px solid #f1f5f9;
      border-radius: 20px;
      font-size: 1rem;
      font-weight: 700;
      transition: all 0.3s ease;
      background: #f8fafc;
      color: var(--primary);
    }

    .form-group input:focus {
      border-color: var(--primary);
      background: white;
      box-shadow: 0 10px 20px rgba(3, 79, 32, 0.05);
      outline: none;
    }

    .btn-login-submit {
      width: 100%;
      padding: 1.2rem;
      background: var(--primary);
      color: white;
      border: none;
      border-radius: 20px;
      font-size: 1.1rem;
      font-weight: 800;
      cursor: pointer;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      margin-top: 1.5rem;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
    }

    .btn-login-submit:hover {
      background: var(--primary);
      box-shadow: 0 15px 30px rgba(3, 79, 32, 0.2);
    }

    @media (max-width: 900px) {
      .login-visual { display: none; }
      .back-home { top: 20px; left: 20px; }
      .login-form-side { padding: 3rem; }
    }
  </style>
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
  <div class="back-home">
    <a href="/"><i class="fas fa-arrow-left"></i> Beranda</a>
  </div>

  <div class="login-container">
    <div class="login-visual">
      <div>
        <div class="role-badge" id="role-display">Orang Tua</div>
        <h2>Portal Pendidikan PAUD ATTHOHIRIYYAH</h2>
        <p style="font-size: 1.2rem; opacity: 0.9; line-height: 1.6;">Membangun masa depan cerah melalui pendidikan yang berlandaskan akhlak mulia.</p>
      </div>
    </div>
    
    <div class="login-form-side">
      <div class="login-header">
        <h1>Selamat Datang</h1>
        <p>Silakan masuk ke akun Anda</p>
      </div>

      <div class="form-group">
        <label for="email">Alamat Email</label>
        <div class="input-wrapper">
          <i class="fas fa-envelope"></i>
          <input type="email" id="email" placeholder="contoh@paud.local">
        </div>
      </div>

      <div class="form-group">
        <label for="password">Kata Sandi</label>
        <div class="input-wrapper">
          <i class="fas fa-lock"></i>
          <input type="password" id="password" placeholder="••••••••">
        </div>
      </div>

      <button class="btn-login-submit" onclick="handleLogin()">
        Masuk Sekarang <i class="fas fa-chevron-right"></i>
      </button>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    const urlParams = new URLSearchParams(window.location.search);
    const role = urlParams.get('role') || 'orang-tua';
    const displayRole = role.charAt(0).toUpperCase() + role.slice(1).replace('-', ' ');
    document.getElementById('role-display').textContent = displayRole;

    async function handleLogin() {
      const email = document.getElementById('email').value;
      const password = document.getElementById('password').value;
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      if (!email || !password) {
        return Swal.fire({
          icon: 'warning',
          title: 'Oops...',
          text: 'Email dan password harus diisi!',
          confirmButtonColor: '#034F20'
        });
      }

      const btn = document.querySelector('.btn-login-submit');
      btn.disabled = true;
      btn.innerHTML = 'Memverifikasi... <i class="fas fa-spinner fa-spin"></i>';

      try {
        const res = await fetch('/auth/login', {
          method: 'POST',
          headers: { 
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: JSON.stringify({ email, password }),
        });
        const j = await res.json();
        if (!j || !j.success) {
          Swal.fire({
            icon: 'error',
            title: 'Login Gagal',
            text: j.message || 'Email atau password salah',
            confirmButtonColor: '#034F20'
          });
          return;
        }

        Swal.fire({
          icon: 'success',
          title: 'Berhasil Masuk!',
          text: 'Selamat datang kembali di PAUD ATTHOHIRIYYAH',
          timer: 1500,
          showConfirmButton: false,
          timerProgressBar: true,
          willClose: () => {
            window.location.href = '/dashboard';
          }
        });
      } catch (err) {
        console.error('Login error', err);
        Swal.fire({
          icon: 'error',
          title: 'Sistem Error',
          text: 'Terjadi kesalahan saat menghubungi server.',
          confirmButtonColor: '#034F20'
        });
      } finally {
        btn.disabled = false;
        btn.innerHTML = 'Masuk Sekarang <i class="fas fa-chevron-right"></i>';
      }
    }
  </script>
</body>
</html>
