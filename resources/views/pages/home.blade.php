@extends('layout.welcome')


@section('content')


<section class="bg-white text-black reveal-section ">
    <div class="container"> 
        <div class="row align-items-center justify-content-center"> 
            
 <div class="col-lg-6 text-lg-start text-center mb-5 mb-lg-0">
    <div class="hero-location-badge animate-on-scroll">
        <div class="icon-container">
            <i class="bi bi-geo-alt-fill"></i>
        </div>
        <span>Yogyakarta, Indonesia</span>
    </div>
    <h1 class="display-4 fw-bold animate-on-scroll">Zenifen Caesarof Agusti</h1>
    <p class="lead opacity-75 mb-0 animate-on-scroll">Web Developer & Front End Engineer</p>
    <p class="text-muted small mb-4 animate-on-scroll">Crafting high-quality digital solutions with precision and code.</p>

    <div class="row g-3 justify-content-center justify-content-lg-start mt-3">
    <div class="col-auto">
        <h5 class="fw-bold mb-0">5+</h5>
        <small class="text-muted">Tech Explored</small>
    </div>
    <div class="col-auto border-start ps-3">
        <h5 class="fw-bold mb-0">Open</h5>
        <small class="text-muted">To Collaboration</small>
    </div>
</div>
</div>

            <div class="col-lg-6 d-flex justify-content-center">
                <div class="profile-animation-wrapper">
                    
                    <div class="animated-gradient-bg"></div>
                    <div class="pulsing-circle-outer"></div>
                    <div class="pulsing-circle-inner"></div>
                    <div class="profile-picture-container">
                        <img src="{{ asset('img/foto_portofolio_animate.png') }}" class="img-fluid profile-image" alt="Zenifen">
                    </div>
                    <div class="ornamen-box computer">🖥️</div>
                    <div class="ornamen-box greek">🏛</div>
                   <div class="status-badge">
    <span class="dot"></span> 
    <span class="status-text">Focused on Growth</span>
    <span id="live-clock">00:00</span>
</div>
                </div> </div> </div> </div> </section>

<section id="about" class="about-section reveal-section py-5 bg-white">
    <div class="container">
        <div class="card profile-card-horizontal">
            <div class="row g-0 align-items-center">
                
                <!-- IMAGE COLUMN -->
                <div class="col-md-4 profile-img-container bg-white text-center py-4 py-md-5">
                    <div class="image-frame">
                        <div class="image-sweep">
                            <img src="{{ asset('img/foto_about_me.jpeg') }}" alt="Profile Zenifen Agusti">
                        </div>
                    </div>
                </div>
                
                <!-- TEXT COLUMN -->
                <div class="col-md-8 bg-white">
                    <div class="card-body p-3 p-md-4 p-lg-5">
                        
                        <!-- HEADER -->
                        <h6 class="text-uppercase fw-bold text-muted mb-3 animate-text" style="letter-spacing: 2px;">
                            Tentang Saya
                        </h6>
                        
                        <!-- PARAGRAPH 1 -->
                        <p class="text-secondary mb-3 animate-text">
                            Saya adalah seorang web developer yang memiliki ketertarikan besar pada dunia pengembangan website dan teknologi digital. Saya lahir di Jakarta pada 29 Agustus 2007, dan saat ini menjalani kehidupan serta aktivitas sehari-hari di Yogyakarta.
                        </p>
                        
                        <!-- PARAGRAPH 2 -->
                        <p class="text-secondary mb-3 animate-text">
                            Saat ini, saya sedang menempuh pendidikan di Universitas Islam Indonesia dengan jurusan Teknik Informatika, di mana saya terus mengembangkan pemahaman tentang teknologi, pemrograman, dan konsep dasar pengembangan perangkat lunak.
                        </p>
                        
                        <!-- PARAGRAPH 3 -->
                        <p class="text-secondary mb-4 animate-text">
                            Saya memiliki ketertarikan besar dalam mempelajari berbagai bahasa pemrograman, khususnya HTML, CSS, JavaScript, Laravel, dan Python. Dalam proses belajar dan mengembangkan proyek, saya selalu berusaha memahami tidak hanya bagaimana sebuah fitur dibuat, tetapi juga alasan di balik penggunaannya.
                        </p>
                        
                        <!-- SOCIAL BUTTONS -->
                        <div class="mt-4 animate-buttons">
                            <a href="https://www.github.com/Zenn-Web"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="social-link github"
                               aria-label="GitHub">
                                <i class="bi bi-github"></i>
                            </a>

                            <a href="https://www.linkedin.com/in/zen-agusti-2928ba38a"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="social-link linkedin"
                               aria-label="LinkedIn">
                                <i class="bi bi-linkedin"></i>
                            </a>

                            <a href="https://www.instagram.com/zenagust_"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="social-link instagram"
                               aria-label="Instagram">
                                <i class="bi bi-instagram"></i>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
               


<section id="skills" class="py-5 my-5 bg-white reveal-section text-dark d-flex" style="scroll-margin-top: 100px;"> 
    <div class="container py-5"> <div class="text-center mb-5">
            <h2 class="fw-bold display-6">Keahlian Saya</h2>
            <p class="text-muted">Beberapa teknologi yang sedang saya pelajari untuk membangun website.</p>
        </div>

        <div class="row g-5 justify-content-center">
            
            <div class="col-lg-3 col-md-6">
                <div class="card skill-card h-100 text-center border-0 shadow py-4">
                    <div class="card-body">
                        <img src="{{ asset('img/HTML5_logo_and_wordmark.svg.png') }}" class="img-fluid mb-4" style="height: 80px; object-fit: contain;">
                        <h4 class="fw-bold mb-2">HTML</h4>
                        <p class="text-muted mb-0">Dasar utama struktur halaman website.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card skill-card h-100 text-center border-0 shadow py-4">
                    <div class="card-body">
                        <img src="{{ asset('img/CSS-Logo-2011.png') }}" class="img-fluid mb-4" style="height: 80px; object-fit: contain;">
                        <h4 class="fw-bold mb-2">CSS</h4>
                        <p class="text-muted mb-0">Mengatur tampilan dan estetika website.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card skill-card h-100 text-center border-0 shadow py-4">
                    <div class="card-body">
                        <img src="{{ asset('img/javascript_logo.png') }}" class="img-fluid mb-4" style="height: 80px; object-fit: contain;">
                        <h4 class="fw-bold mb-2">JavaScript</h4>
                        <p class="text-muted mb-0">Logika pemrograman dan pengolahan data.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card skill-card h-100 text-center border-0 shadow py-4">
                    <div class="card-body">
                        <img src="{{ asset('img/laravel_logo-removebg-preview.png') }}" class="img-fluid mb-4" style="height: 80px; object-fit: contain;">
                        <h4 class="fw-bold mb-2">Laravel</h4>
                        <p class="text-muted mb-0">Framework profesional untuk sistem backend.</p>
                    </div>
                </div>
            </div>

        </div> </div> </section>


<section id="resources" class="my-5 py-5 bg-white reveal-section">
    <div class="container">
        
        <div class="mb-5">
            <h2 class="fw-bold text-dark mb-1">Projek Saya</h2>
            <div style="width: 40px; height: 3px; "></div>
        </div>

        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card card-elegant border-0 shadow-sm h-100 overflow-hidden">
                    
                    <div class="image-container position-relative overflow-hidden">
                        <img src="{{ asset('img/eyegilv2.porto.png') }}" class="img-hover" alt="UX Audit">
                    </div>

                    <div class="position-absolute top-0 start-0 m-3 d-flex flex-wrap gap-2" style="z-index: 10;">
<span class="badge rounded-pill bg-dark border border-secondary px-3 py-2">
        UI/UX Design
    </span>

    <span class="badge rounded-pill bg-dark border border-secondary px-3 py-2">
        Digital Business
    </span>

    <span class="badge rounded-pill bg-dark border border-secondary px-3 py-2">
        Service provider
    </span>
            
                    </div>
                    <div class="card-body px-4 pb-4">
                        <h5 class="fw-bold mb-2">Company Website Development – Eyegil.com</h5>
                        <p class="text-muted small mb-4">
                        Berperan sebagai bagian dari tim developer Eyegil.co yang terlibat dalam pengembangan berbagai proyek website untuk mendukung kebutuhan digital.
                        </p>
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <a href="https://eyegil.com" class="text-decoration-none fw-bold link-primary small">Lihat Project</a>
                            <span class="text-secondary small" style="font-size: 0.7rem;">2025</span>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card card-elegant border-0 shadow-sm h-100 overflow-hidden">
                    
                    <div class="image-container position-relative overflow-hidden">
                        <img src="{{ asset('img/UMKM-tumbuh.porto.png') }}" class="img-hover" alt="UMKM TUMBUH">
                    </div>

                    <div class="position-absolute top-0 start-0 m-3 d-flex flex-wrap gap-2" style="z-index: 10;">
                        <span class="badge rounded-pill bg-dark border border-secondary px-3 py-2">
                            UI/UX Design
                        </span>
                        <span class="badge rounded-pill bg-dark border border-secondary px-3 py-2">
                            Development
                        </span>
                    </div>

                    <div class="card-body px-4 pb-4">
                        <h5 class="fw-bold mb-2">UMKM Business Management Platform – TUMBUH</h5>
                        <p class="text-muted small mb-4">
                            Competition Project — Platform berbasis AI untuk membantu UMKM mengelola bisnis, tracking penjualan, dan analisis data.
                        </p>
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <a href="https://tumbuh-app-web.vercel.app" class="text-decoration-none fw-bold link-primary small">Lihat Project</a>
                            <span class="text-secondary small" style="font-size: 0.7rem;">2026</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<section id="contact" class="contact-section reveal-section">
    <div class="contact-container">
        <div class="contact-info">
            <h2 class="contact-title fw-bold" >Get in Touch</h2>
            <p class="contact-subtitle">I'd like to hear from you!</p>
            
            <div class="contact-details">
                <div class="detail-item mb-2" style="opacity: 1; transform: none;">
                    <i class="bi bi-geo-alt-fill"></i>
                    <span class="text-dark fw-medium" style="font-size: 0.95rem;">Yogyakarta, Indonesia</span>
                </div>
                <div class="detail-item">
                    <i class="bi bi-envelope"></i>
                    <a href="mailto:zenifenagusti70@gmail.com">zenifenagusti70@gmail.com</a>
                </div>
            </div>

            <div class="contact-socials">
                <a href="https://www.github.com/Zenn-Web" class="social-icon"><i class="bi bi-github"></i></a>
                <a href="https://www.linkedin.com/in/zen-agusti-2928ba38a?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=android_app" class="social-icon"><i class="bi bi-linkedin"></i></a>
                <a href="https://www.instagram.com/zenagust_?igsh=MW5jcXZsNGx1ajY1dw==" class="social-icon"><i class="bi bi-instagram"></i></a>
            </div>
        </div>

        <div class="contact-form-wrapper">
           <form action="{{ route('contact.store') }}" method="POST" class="contact-form">
    @csrf
    
    {{-- Tampilkan pesan sukses --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    {{-- Tampilkan error validasi --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <div class="form-row">
    <div class="form-group">
        <label>Nama Depan</label>
        <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="Nama depan Anda">
    </div>
    <div class="form-group">
        <label>Nama Belakang</label>
        <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Nama belakang Anda">
    </div>
</div>
<div class="form-group">
    <label>Email *</label>
    <input type="email" name="email" value="{{ old('email') }}" required placeholder="email@contoh.com">
</div>
<div class="form-group">
    <label>Pesan</label>
    <textarea name="message" rows="4" placeholder="Apa yang bisa saya bantu?">{{ old('message') }}</textarea>
</div>
<div class="button-wrapper">
    <button type="submit" class="btn-send-contact">Kirim</button>
</div>

</form>
        </div>
        </div>
</section>

@endsection
