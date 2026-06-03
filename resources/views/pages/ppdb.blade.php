<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penerimaan Siswa Baru - PAUD ATTHOHIRIYYAH</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Quicksand:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .page-header {
            background: linear-gradient(rgba(3, 79, 32, 0.9), rgba(3, 79, 32, 0.9)), url('{{ asset('img/Banner01.jpeg') }}');
            background-size: cover; background-position: center; padding: 5rem 0 10rem; color: white; text-align: center;
        }
        .form-container { max-width: 900px; margin: -80px auto 5rem; background: white; border-radius: 40px; padding: 0; overflow: hidden; box-shadow: 0 30px 60px rgba(0,0,0,0.1); position: relative; z-index: 5; }
        
        /* Progress Bar */
        .progress-stepper { display: flex; justify-content: space-between; padding: 3rem 4rem; background: #f8fafc; border-bottom: 1px solid #edf2f7; }
        .step-item { flex: 1; text-align: center; position: relative; }
        .step-item:not(:last-child)::after { content: ''; position: absolute; top: 20px; left: 50%; width: 100%; height: 3px; background: #e2e8f0; z-index: 1; }
        .step-item.active:not(:last-child)::after { background: var(--secondary); }
        .step-circle { width: 45px; height: 45px; border-radius: 50%; background: white; border: 3px solid #e2e8f0; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; position: relative; z-index: 2; font-weight: 800; color: #94a3b8; transition: all 0.3s ease; }
        .step-item.active .step-circle { border-color: var(--secondary); background: var(--secondary); color: white; }
        .step-item.completed .step-circle { border-color: var(--secondary); background: white; color: var(--secondary); }
        .step-label { font-size: 0.8rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; }
        .step-item.active .step-label { color: var(--primary); }

        .form-content { padding: 4rem; }
        .form-step { display: none; animation: fadeIn 0.5s ease; }
        .form-step.active { display: block; }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        .form-section-title { margin-bottom: 2.5rem; }
        .form-section-title h3 { font-size: 1.8rem; color: var(--primary); margin-bottom: 0.5rem; }
        .form-section-title p { color: var(--text-muted); font-size: 1rem; }

        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 25px; }
        .form-group { margin-bottom: 1.8rem; }
        .form-group label { display: block; margin-bottom: 0.8rem; font-weight: 700; color: var(--text-dark); font-size: 0.95rem; }
        .form-group input, .form-group select, .form-group textarea { 
            width: 100%; padding: 1.1rem 1.4rem; border: 2px solid #edf2f7; border-radius: 16px; 
            font-family: inherit; font-size: 1rem; transition: all 0.3s ease; outline: none; background: #fcfdfe;
        }
        .form-group input:focus, .form-group select:focus { border-color: var(--primary); background: white; box-shadow: 0 10px 20px rgba(99, 102, 241, 0.05); }
        
        .navigation-buttons { display: flex; justify-content: space-between; margin-top: 4rem; padding-top: 2rem; border-top: 2px solid #f8fafc; }
        .btn-nav { padding: 1rem 2.5rem; border-radius: 50px; font-weight: 800; font-size: 1rem; cursor: pointer; transition: all 0.3s ease; border: none; display: flex; align-items: center; gap: 10px; }
        .btn-prev { background: #f1f5f9; color: #475569; }
        .btn-prev:hover { background: #e2e8f0; }
        .btn-next { background: var(--primary); color: white; margin-left: auto; }
        .btn-next:hover { background: var(--primary-light); transform: translateX(5px); }
        .btn-submit { background: var(--secondary); color: white; margin-left: auto; }
        .btn-submit:hover { background: var(--secondary-light); transform: translateY(-2px); }

        .file-upload-wrapper { border: 2px dashed #e2e8f0; padding: 2rem; border-radius: 20px; text-align: center; transition: all 0.3s ease; background: #fcfdfe; cursor: pointer; }
        .file-upload-wrapper:hover { border-color: var(--primary); background: rgba(99, 102, 241, 0.02); }
        .file-upload-wrapper i { font-size: 2rem; color: #cbd5e0; margin-bottom: 1rem; }
        
        @media (max-width: 768px) { 
            .grid-2 { grid-template-columns: 1fr; } 
            .form-content { padding: 2rem; }
            .progress-stepper { padding: 2rem 1rem; }
            .step-label { display: none; }
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    @include('pages.partials.navbar')

    <header class="page-header">
        <div class="container">
            <h1 style="color: white !important; font-size: clamp(2.5rem, 6vw, 4rem); font-weight: 900; margin-bottom: 1rem;">Mulai Masa Depan Si Kecil</h1>
            <p style="font-size: 1.25rem; opacity: 0.9; max-width: 700px; margin: 0 auto; font-weight: 600;">Daftarkan buah hati Anda ke PAUD ATTHOHIRIYYAH melalui formulir online yang praktis dan mudah.</p>
        </div>
    </header>

    <main class="container">
        <div class="form-container">
            <!-- Progress Stepper -->
            <div class="progress-stepper">
                <div class="step-item active" id="step-1-indicator">
                    <div class="step-circle">1</div>
                    <div class="step-label">Pendaftaran</div>
                </div>
                <div class="step-item" id="step-2-indicator">
                    <div class="step-circle">2</div>
                    <div class="step-label">Data Siswa</div>
                </div>
                <div class="step-item" id="step-3-indicator">
                    <div class="step-circle">3</div>
                    <div class="step-label">Orang Tua</div>
                </div>
                <div class="step-item" id="step-4-indicator">
                    <div class="step-circle">4</div>
                    <div class="step-label">Dokumen</div>
                </div>
            </div>

            <form id="ppdbForm" class="form-content" enctype="multipart/form-data">
                <!-- STEP 1: JENIS PENDAFTARAN -->
                <div class="form-step active" id="step-1">
                    <div class="form-section-title">
                        <h3>Informasi Pendaftaran</h3>
                        <p>Tentukan kategori dan jalur pendaftaran buah hati Anda.</p>
                    </div>
                    <div class="grid-2">
                        <div class="form-group">
                            <label>Jenis Pendaftaran *</label>
                            <select name="jenis_pendaftaran" required>
                                <option value="">Pilih Jenis Pendaftaran</option>
                                <option value="Siswa Baru">Siswa Baru</option>
                                <option value="Pindahan">Siswa Pindahan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Jalur Pendaftaran *</label>
                            <select name="jalur_pendaftaran" required>
                                <option value="">Pilih Jalur Pendaftaran</option>
                                <option value="Reguler">Reguler</option>
                                <option value="Prestasi">Prestasi</option>
                                <option value="Afirmasi">Afirmasi</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid-2">
                        <div class="form-group">
                            <label>NIK / No. KITAS Calon Siswa *</label>
                            <input type="text" name="nik" placeholder="16 Digit NIK" required maxlength="16">
                        </div>
                        <div class="form-group">
                            <label>Nama Sekolah Asal</label>
                            <input type="text" name="sekolah_asal" placeholder="Tulis '-' jika tidak ada">
                        </div>
                    </div>
                    <div class="navigation-buttons">
                        <button type="button" class="btn-nav btn-next" onclick="nextStep(1)">Lanjutkan <i class="fas fa-arrow-right"></i></button>
                    </div>
                </div>

                <!-- STEP 2: DATA PRIBADI -->
                <div class="form-step" id="step-2">
                    <div class="form-section-title">
                        <h3>Biodata Calon Siswa</h3>
                        <p>Pastikan data sesuai dengan Akta Kelahiran resmi.</p>
                    </div>
                    <div class="form-group">
                        <label>Nama Lengkap *</label>
                        <input type="text" name="nama_lengkap" placeholder="Sesuai Akta Kelahiran" required>
                    </div>
                    <div class="grid-2">
                        <div class="form-group">
                            <label>Jenis Kelamin *</label>
                            <select name="jenis_kelamin" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Agama *</label>
                            <select name="agama" required>
                                <option value="">Pilih Agama</option>
                                <option value="Islam">Islam</option>
                                <option value="Kristen">Kristen</option>
                                <option value="Katolik">Katolik</option>
                                <option value="Hindu">Hindu</option>
                                <option value="Budha">Budha</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid-2">
                        <div class="form-group"><label>Tempat Lahir *</label><input type="text" name="tempat_lahir" required></div>
                        <div class="form-group"><label>Tanggal Lahir *</label><input type="date" name="tanggal_lahir" required></div>
                    </div>
                    <div class="form-group">
                        <label>Alamat Domisili Lengkap *</label>
                        <textarea name="alamat_jalan" rows="3" placeholder="Nama jalan, RT/RW, dan No. Rumah" required></textarea>
                    </div>
                    <div class="grid-2">
                        <div class="form-group"><label>Kecamatan *</label><input type="text" name="kecamatan" required></div>
                        <div class="form-group"><label>Kabupaten/Kota *</label><input type="text" name="kabupaten_kota" required></div>
                    </div>
                    <div class="navigation-buttons">
                        <button type="button" class="btn-nav btn-prev" onclick="prevStep(2)"><i class="fas fa-arrow-left"></i> Kembali</button>
                        <button type="button" class="btn-nav btn-next" onclick="nextStep(2)">Lanjutkan <i class="fas fa-arrow-right"></i></button>
                    </div>
                </div>

                <!-- STEP 3: DATA ORANG TUA -->
                <div class="form-step" id="step-3">
                    <div class="form-section-title">
                        <h3>Data Orang Tua</h3>
                        <p>Informasi Ayah dan Ibu kandung calon siswa.</p>
                    </div>
                    <div style="background: #f8fafc; padding: 2rem; border-radius: 20px; margin-bottom: 2rem;">
                        <h4 style="margin-bottom: 1.5rem; color: var(--primary); display: flex; align-items: center; gap: 10px;"><i class="fas fa-user-friends"></i> Data Ayah Kandung</h4>
                        <div class="form-group"><label>Nama Lengkap Ayah *</label><input type="text" name="nama_ayah" required></div>
                        <div class="grid-2">
                            <div class="form-group"><label>NIK Ayah *</label><input type="text" name="nik_ayah" required></div>
                            <div class="form-group"><label>Pekerjaan *</label><input type="text" name="pekerjaan_ayah" required></div>
                        </div>
                    </div>
                    <div style="background: #fff5f5; padding: 2rem; border-radius: 20px;">
                        <h4 style="margin-bottom: 1.5rem; color: #e53e3e; display: flex; align-items: center; gap: 10px;"><i class="fas fa-female"></i> Data Ibu Kandung</h4>
                        <div class="form-group"><label>Nama Lengkap Ibu *</label><input type="text" name="nama_ibu" required></div>
                        <div class="grid-2">
                            <div class="form-group"><label>NIK Ibu *</label><input type="text" name="nik_ibu" required></div>
                            <div class="form-group"><label>Pekerjaan *</label><input type="text" name="pekerjaan_ibu" required></div>
                        </div>
                    </div>
                    <div class="navigation-buttons">
                        <button type="button" class="btn-nav btn-prev" onclick="prevStep(3)"><i class="fas fa-arrow-left"></i> Kembali</button>
                        <button type="button" class="btn-nav btn-next" onclick="nextStep(3)">Lanjutkan <i class="fas fa-arrow-right"></i></button>
                    </div>
                </div>

                <!-- STEP 4: DOKUMEN -->
                <div class="form-step" id="step-4">
                    <div class="form-section-title">
                        <h3>Unggah Dokumen</h3>
                        <p>Format file: JPG, PNG, atau PDF. Maksimal 512KB per file.</p>
                    </div>
                    <div class="grid-2">
                        <div class="form-group">
                            <label>Pas Foto Calon Siswa (3x4) *</label>
                            <input type="file" name="pas_foto" accept="image/*" required>
                        </div>
                        <div class="form-group">
                            <label>Kartu Keluarga *</label>
                            <input type="file" name="kartu_keluarga" accept=".jpg,.jpeg,.png,.pdf" required>
                        </div>
                    </div>
                    <div class="grid-2">
                        <div class="form-group">
                            <label>Akta Kelahiran *</label>
                            <input type="file" name="akta_lahir" accept=".jpg,.jpeg,.png,.pdf" required>
                        </div>
                        <div class="form-group">
                            <label>Nomor WhatsApp *</label>
                            <input type="text" name="nomor_hp" placeholder="Contoh: 08123456789" required>
                        </div>
                    </div>
                    
                    <div style="margin-top: 2rem; padding: 2rem; background: var(--background); border-radius: 20px; border: 2px solid #edf2f7;">
                        <label style="display: flex; gap: 15px; cursor: pointer; font-weight: 700; color: var(--text-dark);">
                            <input type="checkbox" required style="width: 25px; height: 25px; border-radius: 6px;">
                            <span>Saya menyatakan bahwa seluruh data yang diisi adalah benar dan dapat dipertanggungjawabkan.</span>
                        </label>
                    </div>

                    <div class="navigation-buttons">
                        <button type="button" class="btn-nav btn-prev" onclick="prevStep(4)"><i class="fas fa-arrow-left"></i> Kembali</button>
                        <button type="submit" class="btn-nav btn-submit">Kirim Pendaftaran <i class="fas fa-paper-plane"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </main>

    @include('pages.partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function showStep(step) {
            document.querySelectorAll('.form-step').forEach(el => el.classList.remove('active'));
            document.getElementById('step-' + step).classList.add('active');
            
            // Update indicators
            document.querySelectorAll('.step-item').forEach((el, index) => {
                const stepNum = index + 1;
                el.classList.remove('active', 'completed');
                if (stepNum === step) el.classList.add('active');
                if (stepNum < step) el.classList.add('completed');
            });

            window.scrollTo({ top: 200, behavior: 'smooth' });
        }

        function validateStep(step) {
            const currentStepEl = document.getElementById('step-' + step);
            const inputs = currentStepEl.querySelectorAll('input[required], select[required], textarea[required]');
            let valid = true;
            
            inputs.forEach(input => {
                if (!input.value) {
                    input.style.borderColor = '#e53e3e';
                    valid = false;
                } else {
                    input.style.borderColor = '#edf2f7';
                }
            });

            if (!valid) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Belum Lengkap',
                    text: 'Harap isi semua kolom yang wajib bertanda bintang (*) sebelum melanjutkan.',
                    confirmButtonColor: '#034F20'
                });
            }
            return valid;
        }

        function nextStep(current) {
            if (validateStep(current)) {
                showStep(current + 1);
            }
        }

        function prevStep(current) {
            showStep(current - 1);
        }

        document.getElementById('ppdbForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const btn = document.querySelector('.btn-submit');

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

            try {
                const res = await fetch('/ppdb/submit', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    body: formData
                });
                const j = await res.json();

                if (j.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Pendaftaran Berhasil!',
                        text: j.message,
                        confirmButtonColor: '#034F20'
                    }).then(() => {
                        window.location.href = '/';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: j.message || 'Terjadi kesalahan saat memproses data.',
                        confirmButtonColor: '#034F20'
                    });
                }
            } catch (err) {
                console.error(err);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan sistem.' });
            } finally {
                btn.disabled = false;
                btn.innerHTML = 'Kirim Pendaftaran <i class="fas fa-paper-plane"></i>';
            }
        });
    </script>
</body>
</html>
