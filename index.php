<?php
$pageTitle = "Industry in Zimbabwe - CZI eDirectory";
$pageDescription = "The industry Hub of all industries";
require_once __DIR__ . '/includes/head.php';
?>
<style>
    /* ========== HERO ========== */
    .hero-section {
        position: relative;
        width: 100%;
        min-height: 100vh;
        background: url('<?= SITE_ROOT ?>/assets/img/hero.png') center/cover no-repeat;
        background-color: #0a1e3d;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .hero-content {
        text-align: center;
        z-index: 10;
        width: 100%;
        padding: 120px 20px 80px;
    }

    .hero-title {
        color: #fff;
        font-size: 4.5rem;
        font-weight: 800;
        max-width: 900px;
        line-height: 1.1;
        margin: 0 auto 35px;
        text-shadow: 0 2px 20px rgba(0,0,0,0.3);
    }

    .hero-buttons {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 14px;
    }

    .hero-btn {
        background-color: #5bc271;
        color: #fff;
        border: none;
        padding: 12px 26px;
        font-size: 14px;
        cursor: pointer;
        border-radius: 3px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
        min-width: 210px;
        text-align: center;
    }

    .hero-btn:hover {
        background-color: #4aaa5f;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(91, 194, 113, 0.4);
        color: #fff;
    }

    /* Discover Section */
    .discover-section {
        text-align: center;
        padding: 50px 20px 40px;
        background: #fff;
    }

    .discover-title { color: #00a000; font-size: 2rem; font-weight: 700; margin-bottom: 10px; }
    .discover-subtitle { color: #c00; font-size: 1.5rem; font-weight: 700; font-style: italic; margin-bottom: 18px; }
    .engineering-text { color: #000; font-size: 1.4rem; font-weight: 700; }

    /* Categories */
    .categories-section {
        background-color: #f0f0f0;
        padding: 35px 20px;
        text-align: center;
    }

    .category-list {
        display: flex; flex-direction: column; align-items: center;
        gap: 12px; margin-bottom: 24px;
    }

    .category-item {
        font-size: 1.4rem; font-weight: 700; color: #222;
        cursor: pointer; transition: color 0.3s; text-decoration: none;
    }

    .category-item:hover { color: #5bc271; }

    .featured-companies-title {
        font-size: 1.5rem; font-weight: 700; color: #222; margin: 30px 0 24px;
    }

    /* ========== CAROUSEL ========== */
    .carousel-wrapper {
        position: relative;
        max-width: 900px;
        margin: 0 auto 30px;
        overflow: hidden;
    }

    .carousel-track {
        display: flex;
        gap: 30px;
        transition: transform 0.5s ease;
    }

    .carousel-slide {
        flex: 0 0 auto;
        width: 280px;
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        border-radius: 4px;
        padding: 15px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }

    .carousel-slide img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .carousel-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255,255,255,0.9);
        border: 1px solid #ddd;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        color: #333;
        transition: all 0.3s;
        z-index: 10;
    }

    .carousel-btn:hover { background: #5bc271; color: #fff; }
    .carousel-btn-left { left: 0; }
    .carousel-btn-right { right: 0; }

    @media (max-width: 768px) {
        .hero-title { font-size: 2rem; }
        .carousel-slide { width: 200px; height: 90px; }
    }

    @media (max-width: 480px) {
        .hero-title { font-size: 1.5rem; }
        .carousel-slide { width: 150px; height: 70px; }
    }
</style>
</head>

<body>

<!-- ========== HEADER ========== -->
<?php require_once __DIR__ . '/includes/navbar.php'; ?>

<!-- ========== HERO ========== -->
<section class="hero-section">
    <div class="hero-content">
        <h1 class="hero-title">Technology &amp; Artificial Intelligence (AI) Shaping The Industry</h1>
        <div class="hero-buttons">
            <a href="industries.php" class="hero-btn">Find Suppliers</a>
            <a href="<?= SITE_ROOT ?>/stakeholder.php?org=CZI&section=directory" class="hero-btn">CZI eDirectory</a>
            <a href="contact.php" class="hero-btn">List Your Business Here!!</a>
        </div>
    </div>
</section>

<!-- ========== CONTENT ========== -->
<main>

    <!-- Discover -->
    <section class="discover-section">
        <h2 class="discover-title">Discover Industries In Zimbabwe</h2>
        <h3 class="discover-subtitle">Featured Companies</h3>
        <p class="engineering-text">Engineering</p>
    </section>

    <!-- Categories + Carousel -->
    <section class="categories-section">
        <div class="category-list">
            <a href="<?= SITE_ROOT ?>/industry.php?slug=auto" class="category-item">Auto Mobile</a>
            <a href="<?= SITE_ROOT ?>/industry.php?slug=construction" class="category-item">Concrete Products</a>
            <a href="<?= SITE_ROOT ?>/industry.php?slug=technology-ict" class="category-item">Security</a>
            <a href="<?= SITE_ROOT ?>/industry.php?slug=manufacturing" class="category-item">Packaging</a>
        </div>

        <h3 class="featured-companies-title">Featured Companies</h3>

        <div class="carousel-wrapper">
            <button class="carousel-btn carousel-btn-left" onclick="slideCarousel(-1)">
                <i class="bi bi-chevron-left"></i>
            </button>
            <div class="carousel-track" id="carouselTrack">
                <div class="carousel-slide"><img src="<?= SITE_ROOT ?>/assets/img/clients/turnall-logo.jpg" alt="Turnall"></div>
                <div class="carousel-slide"><img src="<?= SITE_ROOT ?>/assets/img/clients/earthwave.jpg" alt="Earthwave"></div>
                <div class="carousel-slide"><img src="<?= SITE_ROOT ?>/assets/img/clients/fueltec.jpg" alt="Fueltec"></div>
                <div class="carousel-slide"><img src="<?= SITE_ROOT ?>/assets/img/clients/masimba.jpg" alt="Masimba"></div>
                <div class="carousel-slide"><img src="<?= SITE_ROOT ?>/assets/img/clients/edenvine-mobile-logo.jpg" alt="Edenvine"></div>
                <div class="carousel-slide"><img src="<?= SITE_ROOT ?>/assets/img/clients/asphalt-logo.jpg" alt="Asphalt"></div>
            </div>
            <button class="carousel-btn carousel-btn-right" onclick="slideCarousel(1)">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
    </section>

    <?php require_once __DIR__ . '/includes/footer.php'; ?>

</main>

<!-- Scroll Top -->
<a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS -->
<script src="<?= SITE_ROOT ?>/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= SITE_ROOT ?>/assets/vendor/aos/aos.js"></script>
<script src="<?= SITE_ROOT ?>/assets/vendor/glightbox/js/glightbox.min.js"></script>
<script src="<?= SITE_ROOT ?>/assets/vendor/swiper/swiper-bundle.min.js"></script>
<script src="<?= SITE_ROOT ?>/assets/vendor/waypoints/noframework.waypoints.js"></script>
<script src="<?= SITE_ROOT ?>/assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
<script src="<?= SITE_ROOT ?>/assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
<script src="<?= SITE_ROOT ?>/assets/js/main.js"></script>

<!-- Carousel JS -->
<script>
    var carouselPos = 0;
    var track = document.getElementById('carouselTrack');
    var slides = track.querySelectorAll('.carousel-slide');
    var slideW = 310; // width + gap
    var visibleSlides = 3;

    function slideCarousel(dir) {
        var maxOffset = -(slides.length - visibleSlides) * slideW;
        carouselPos += dir * -slideW;
        if (carouselPos > 0) carouselPos = maxOffset;
        if (carouselPos < maxOffset) carouselPos = 0;
        track.style.transform = 'translateX(' + carouselPos + 'px)';
    }

    // Auto-slide every 3 seconds
    setInterval(function() { slideCarousel(-1); }, 3000);
</script>

</body>
</html>