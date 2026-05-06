<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kurnia Care</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600;800&display=swap" rel="stylesheet">

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    background: #0a0a0a;
    color: white;
}

/* HERO */
.hero {
    height: 100vh;
    position: relative;
    overflow: hidden;
    margin-top: 80px;
}

.slide {
    position: absolute;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    opacity: 0;
    transform: scale(1.1);
    transition: opacity 1.5s ease, transform 6s ease;
}

.slide.active {
    opacity: 1;
    transform: scale(1);
}

.overlay {
    position: absolute;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 2;
}

.hero-content {
    position: absolute;
    top: 50%;
    left: 10%;
    transform: translateY(-50%);
    z-index: 3;
}

.hero h1 {
    font-size: 60px;
    font-weight: 800;
}

.hero p {
    margin-top: 20px;
    color: #ccc;
}

.btn {
    margin-top: 30px;
    display: inline-block;
    padding: 12px 25px;
    background: #00c896;
    border-radius: 5px;
    text-decoration: none;
    color: white;
}

/* SECTION */
.section {
    padding: 80px 10%;
    text-align: center;
}

.cards {
    display: flex;
    gap: 20px;
    margin-top: 40px;
    flex-wrap: wrap;
    justify-content: center;
}

.card {
    background: #111;
    padding: 30px;
    border-radius: 10px;
    width: 260px;
    transition: 0.3s;
}

.card:hover {
    transform: translateY(-10px);
}
</style>
</head>

<body>

@include('components.header')

<section class="hero">

    <div class="slide active" style="background-image: url('{{ asset('images/hero1.jpg') }}')"></div>
    <div class="slide" style="background-image: url('{{ asset('images/hero2.jpg') }}')"></div>
    <div class="slide" style="background-image: url('{{ asset('images/hero3.jpg') }}')"></div>

    <div class="overlay"></div>

    <div class="hero-content">
        <h1>KURNIA CARE</h1>
        <p>Klinik dan sunat modern, aman, dan profesional.</p>
        <a href="#" class="btn">Booking Sekarang</a>
    </div>

</section>

<section class="section" id="about">
    <h2>Kenapa Pilih Kami?</h2>
    <p>Metode modern, Pelayanan terbaik.</p>

    <div class="cards">
        <div class="card">Pelayanan Terbaaik</div>
        <div class="card">Dokter Profesional</div>
        <div class="card">Fasilitas Modern</div>
    </div>
</section>

@include('components.footer')

<script>
let slides = document.querySelectorAll('.slide');
let index = 0;

setInterval(() => {
    slides[index].classList.remove('active');
    index = (index + 1) % slides.length;
    slides[index].classList.add('active');
}, 4000);

// efek header scroll
window.addEventListener("scroll", function() {
    let header = document.querySelector(".header");

    if (window.scrollY > 50) {
        header.style.background = "#000";
    } else {
        header.style.background = "rgba(0,0,0,0.4)";
    }
});
</script>

</body>
</html>