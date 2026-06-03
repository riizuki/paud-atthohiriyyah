<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Ditutup - PAUD ATTHOHIRIYYAH</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { 
            display: flex; 
            flex-direction: column; 
            min-height: 100vh; 
            background: #f8fafc; 
            font-family: 'Quicksand', sans-serif; 
        }
        .content-wrapper { 
            flex: 1; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            padding: 4rem 20px; 
        }
        .closed-card { 
            background: white; 
            padding: 4rem; 
            border-radius: 40px; 
            box-shadow: 0 20px 50px rgba(0,0,0,0.05); 
            text-align: center; 
            max-width: 600px; 
        }
        .icon-box { 
            background: #fee2e2; 
            color: #dc2626; 
            width: 100px; 
            height: 100px; 
            border-radius: 50%; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            margin: 0 auto 2rem; 
            font-size: 3rem; 
        }
    </style>
</head>
<body>
    @include('pages.partials.navbar')
    
    <div class="content-wrapper">
        <div class="closed-card">
            <div class="icon-box"><i class="fas fa-lock"></i></div>
            <h1 style="color: var(--primary); margin-bottom: 1.5rem; font-weight: 900;">Pendaftaran Ditutup</h1>
            <p style="color: #64748b; font-size: 1.1rem; line-height: 1.6;">
                Mohon maaf, periode pendaftaran murid baru untuk saat ini telah ditutup. 
                Silakan kembali lagi di lain waktu.
            </p>
        </div>
    </div>
</body>
</html>
