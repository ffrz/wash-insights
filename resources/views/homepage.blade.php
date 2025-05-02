<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>{{ env('APP_NAME') }}</title>
  <meta name="description" content="">
  <meta name="keywords" content="">
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/css/main.css" rel="stylesheet">
  @vite([])
</head>

<body class="index-page">
  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">
      <a href="./" class="logo d-flex align-items-center me-auto">
        <h1 class="sitename">{{ env('APP_NAME') }}</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#hero" class="active">Beranda</a></li>
          <li><a href="#about">Tentang</a></li>
          <li><a href="#features">Fitur</a></li>
          <li><a href="#contact">Hubungi Kami</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <a class="btn-getstarted" href="{{ route('admin.auth.login') }}">Masuk</a>
      <a class="btn-getstarted" href="{{ route('admin.auth.register') }}">Daftar</a>

    </div>
  </header>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="section hero light-background">

      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-6 order-lg-1 d-flex flex-column justify-content-center order-2" data-aos="fade-up">
            <h2>Kelola Data Servis dengan Mudah dan Transparan</h2>
            <p>FixSync membantu Anda mengelola data servis, melacak progres perbaikan, dan meningkatkan kepercayaan
              pelanggan—semua dalam satu platform modern dan efisien.</p>
          </div>
          <div class="col-lg-6 order-lg-2 hero-img order-1" data-aos="zoom-out" data-aos-delay="200">
            <img src="assets/img/hero-img.jpg" class="img-fluid" style="border-radius: 10px;" alt="">
          </div>
        </div>
      </div>

    </section><!-- /Hero Section -->

    <!-- About Section -->
    <section id="about" class="section about">

      <div class="container">

        <h3 class="text-center">Solusi Terbaik untuk Manajemen Servis Online Anda</h3>
        <p class="mb-5 text-center">
          FixSync adalah platform manajemen servis modern yang dirancang untuk membantu bisnis servis Anda lebih
          terorganisir, transparan, dan efisien. Dengan fitur pelacakan yang intuitif, pelanggan Anda dapat dengan
          mudah memantau progres servis mereka kapan saja, di mana saja.
        </p>
        <div class="row gy-3 items-center">
          <div class="col-lg-6 d-flex flex-column justify-content-center" data-aos="fade-up" data-aos-delay="100">
            <img src="assets/img/about-img.jpg" alt="" class="img-fluid" style="border-radius:10px;">
          </div>
          <div class="col-lg-6 d-flex flex-column justify-content-center" data-aos="fade-up" data-aos-delay="200">
            <div class="about-content ps-lg-3 ps-0">
              <ul>
                <li>
                  <i class="bi bi-activity"></i>
                  <div>
                    <h4>Pelacakan Servis yang Transparan</h4>
                    <p>Pelanggan dapat memeriksa status servis secara real-time melalui sistem kami. Tidak perlu lagi
                      menelepon untuk menanyakan status!</p>
                  </div>
                </li>
                <li>
                  <i class="bi bi-person-bounding-box"></i>
                  <div>
                    <h4>Manajemen Data yang Mudah</h4>
                    <p>Kelola data pelanggan, riwayat servis, dan biaya dengan cepat dalam satu platform.</p>
                  </div>
                </li>
                <li>
                  <i class="bi bi-hand-thumbs-up"></i>
                  <div>
                    <h4>Peningkatan Kepercayaan Pelanggan</h4>
                    <p>Dengan informasi yang transparan, pelanggan Anda akan merasa lebih percaya pada layanan yang Anda
                      berikan.</p>
                  </div>
                </li>
                <li>
                  <i class="bi bi-journal-check"></i>
                  <div>
                    <h4>Efisiensi untuk Tim Anda</h4>
                    <p>Automasi tugas-tugas administratif seperti pembuatan laporan dan pemberitahuan status servis.</p>
                  </div>
                </li>
              </ul>
            </div>

          </div>
        </div>

      </div>

    </section><!-- /About Section -->

    <!-- Features Section -->
    <section id="features" class="services section light-background">

      <!-- Section Title -->
      <div class="section-title container" data-aos="fade-up">
        <h2>Fitur Utama</h2>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4">

          <div class="col-xl-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="100">
            <div class="service-item position-relative">
              <div class="icon"><i class="bi bi-activity icon"></i></div>
              <h4><a href="service-details.html" class="stretched-link">Pelacakan Status Servis</a></h4>
              <p>Memungkinkan pelanggan untuk mengetahui status perangkat mereka mulai dari diterima hingga selesai
                diperbaiki.</p>
            </div>
          </div><!-- End Service Item -->

          <div class="col-xl-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="200">
            <div class="service-item position-relative">
              <div class="icon"><i class="bi bi-bell icon"></i></div>
              <h4><a href="service-details.html" class="stretched-link">Notifikasi Otomatis</a></h4>
              <p>Kirim pemberitahuan kepada pelanggan untuk setiap pembaruan status.</p>
            </div>
          </div><!-- End Service Item -->

          <div class="col-xl-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="300">
            <div class="service-item position-relative">
              <div class="icon"><i class="bi bi-graph-up-arrow icon"></i></div>
              <h4><a href="service-details.html" class="stretched-link">Laporan dan Analisis</a></h4>
              <p>Pantau performa bisnis Anda dengan laporan lengkap dan grafik interaktif.</p>
            </div>
          </div><!-- End Service Item -->

          <div class="col-xl-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="400">
            <div class="service-item position-relative">
              <div class="icon"><i class="bi bi-people icon"></i></div>
              <h4><a href="service-details.html" class="stretched-link">Manajemen Pelanggan</a></h4>
              <p>Simpan dan kelola informasi pelanggan dengan aman dan efisien.</p>
            </div>
          </div><!-- End Service Item -->

        </div>

      </div>

    </section><!-- /Services Section -->

    <!-- Contact Section -->
    <section id="contact" class="contact section">

      <!-- Section Title -->
      <div class="container" data-aos="fade-up">
        <h2 class="text-center">Bagaimana FixSync Membantu Bisnis Anda?</h2>
        <div class="text-center">
          <p><strong>1. Peningkatan Produktivitas:</strong> Fokus pada servis, bukan administrasi.</p>
          <p><strong>2. Pengalaman Pelanggan yang Lebih Baik:</strong> Berikan kemudahan dan transparansi kepada pelanggan Anda.</p>
          <p><strong>3. Integrasi Mudah:</strong> Kompatibel dengan perangkat mobile untuk akses kapan saja.</p>
        </div>
      </div><!-- End Section Title -->

    </section><!-- /Contact Section -->

  </main>

  <footer id="footer" class="footer position-relative">

    <div class="footer-newsletter">
      <div class="container">
        <div class="row justify-content-center text-center">
          <div class="col-lg-12">
            <h4>Mulai Sekarang!</h4>
            <p>Daftar Sekarang dan nikmati pengalaman manajemen servis yang lebih mudah dan modern dengan FixSync!</p>
            <a href="{{ route('admin.auth.register') }}" class="btn-get-started">Daftar</a>
          </div>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="row text-center">
        <div class="col-lg-12 mt-5 text-center">
          <h4>Hubungi Kami</h4>
          <p class="mt-3"><strong>Telepon / WA:</strong> <a href="https://wa.me/6285317404760">+6285-3174-04760</a>
          </p>
          <p><strong>Email:</strong> <span>fixsync@shiftech.my.id</span></p>
        </div>
      </div>
      <!--
        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Tautan Lainnya</h4>
          <ul>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Home</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#about">Tentang</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#features">Fitur</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#contact">Hubungi Kami</a></li>
          </ul>
        </div>


        <div class="col-lg-4 col-md-12">
          <h4>Follow Us</h4>
          <p>Cras fermentum odio eu feugiat lide par naso tierra videa magna derita valies</p>
          <div class="social-links d-flex">
            <a href=""><i class="bi bi-twitter-x"></i></a>
            <a href=""><i class="bi bi-facebook"></i></a>
            <a href=""><i class="bi bi-instagram"></i></a>
            <a href=""><i class="bi bi-linkedin"></i></a>
          </div>
        </div>-->

    </div>

    <div class="copyright container mt-4 text-center">
      <p>© {{ date('Y') }} <strong class="sitename px-1"><a href="https://shiftech.my.id">Shiftech
            Indonesia</a></strong> <span>All Rights Reserved</span></p>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you've purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
        Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
      </div>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>
