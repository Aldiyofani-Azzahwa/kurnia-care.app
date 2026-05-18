<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kurnia Care</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600;800&display=swap" rel="stylesheet">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

body{
    background:#0a0a0a;
    color:white;
    overflow-x:hidden;
}

/* HERO */
.hero{
    height:100vh;
    position:relative;
    overflow:hidden;
    margin-top:70px;
}

.slide{
    position:absolute;
    width:100%;
    height:100%;
    background-size:cover;
    background-position:center;
    opacity:0;
    transform:scale(1.1);
    transition:opacity 1.5s ease, transform 6s ease;
}

.slide.active{
    opacity:1;
    transform:scale(1);
}

.overlay{
    position:absolute;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.5);
    z-index:2;
}

.hero-content{
    position:absolute;
    top:50%;
    left:10%;
    right:10%;
    transform:translateY(-50%);
    z-index:3;
}

.hero h1{
    font-size:clamp(30px,6vw,65px);
    font-weight:800;
}

.hero p{
    margin-top:20px;
    color:#ccc;
    font-size:clamp(14px,2vw,18px);
    max-width:600px;
    line-height:1.8;
}

.btn{
    margin-top:30px;
    display:inline-block;
    padding:13px 25px;
    background:#00c896;
    border-radius:8px;
    text-decoration:none;
    color:white;
    transition:0.3s;
}

.btn:hover{
    background:#00a97f;
}

/* SECTION */
.section{
    padding:90px 5%;
    text-align:center;
}

.section h2{
    font-size:clamp(28px,5vw,45px);
}

.section-desc{
    margin-top:15px;
    color:#aaa;
    max-width:800px;
    margin-inline:auto;
    line-height:1.8;
}

/* CARD */
.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
    gap:20px;
    margin-top:50px;
    align-items:start;
}

.info-card{
    background:#111;
    border-radius:18px;
    overflow:hidden;
    cursor:pointer;
    transition:0.3s;
    border:1px solid rgba(255,255,255,0.06);
    text-align:left;
    align-self:start;
}

.info-card:hover{
    transform:translateY(-10px);
    border-color:#00c896;
    box-shadow:0 10px 25px rgba(0,200,150,0.15);
}

.card-image{
    width:100%;
    height:200px;
    object-fit:cover;
}

.card-content{
    padding:20px;
}

.info-top{
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.info-top h3{
    font-size:20px;
}

.info-top span{
    font-size:28px;
    color:#00c896;
    transition:0.3s;
}

.info-detail{
    max-height:0;
    overflow:hidden;
    transition:0.4s ease;
    color:#bbb;
    line-height:1.9;
    font-size:14px;
}

.info-card.active .info-detail{
    max-height:200px;
    margin-top:18px;
}

.info-card.active span{
    transform:rotate(45deg);
}

/* METODE */
.metode-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
    gap:20px;
    margin-top:50px;
}

.metode-card{
    background:#111;
    border-radius:18px;
    overflow:hidden;
    text-decoration:none;
    color:white;
    transition:0.3s;
    border:1px solid rgba(255,255,255,0.06);
}

.metode-card:hover{
    transform:translateY(-10px);
    border-color:#00c896;
    box-shadow:0 10px 25px rgba(0,200,150,0.15);
}

.metode-card img{
    width:100%;
    height:220px;
    object-fit:cover;
}

.metode-content{
    padding:20px;
    text-align:left;
}

.metode-content h3{
    margin-bottom:10px;
    font-size:22px;
}

.metode-content p{
    color:#aaa;
    line-height:1.8;
    font-size:14px;
}

/* RESPONSIVE */
@media(max-width:768px){

    .hero{
        margin-top:60px;
    }

    .hero-content{
        left:5%;
        right:5%;
    }

    .section{
        padding:70px 20px;
    }

    .btn{
        padding:11px 20px;
        font-size:14px;
    }

    .card-image{
        height:190px;
    }

    .metode-card img{
        height:200px;
    }
}
</style>
</head>

<body>

@include('components.header')

<!-- HERO -->
<section class="hero">

    <div class="slide active"
    style="background-image:url('{{ asset('images/hero1.jpg') }}')">
    </div>

    <div class="slide"
    style="background-image:url('{{ asset('images/hero2.jpg') }}')">
    </div>

    <div class="slide"
    style="background-image:url('{{ asset('images/hero3.jpg') }}')">
    </div>

    <div class="overlay"></div>

    <div class="hero-content">

        <h1>KURNIA CARE</h1>

        <p>
            Klinik dan layanan sunat modern dengan fasilitas lengkap,
            dokter profesional, dan metode terkini untuk memberikan
            pengalaman yang nyaman bagi setiap pasien.
        </p>

        <a href="#" class="btn">
            Booking Sekarang
        </a>

    </div>

</section>

<!-- TENTANG -->
<section class="section" id="about">

    <h2>Tentang Kami</h2>

    <p class="section-desc">
        Kurnia Care hadir sebagai klinik sunat modern yang mengutamakan
        keamanan, kenyamanan, dan pelayanan profesional dengan teknologi
        terkini untuk seluruh pasien.
    </p>

    <!-- CARD -->
    <div class="cards">

        <!-- CARD 1 -->
        <div class="info-card" onclick="toggleCard(this)">

            <img src="{{ asset('images/pelayanan.jpg') }}"
            class="card-image">

            <div class="card-content">

                <div class="info-top">
                    <h3>Pelayanan Terbaik</h3>
                    <span>+</span>
                </div>

                <div class="info-detail">
                    Kami memberikan pelayanan cepat, ramah,
                    dan profesional agar pasien merasa nyaman
                    sejak pertama datang ke klinik.
                </div>

            </div>

        </div>

        <!-- CARD 2 -->
        <div class="info-card" onclick="toggleCard(this)">

            <img src="{{ asset('images/dokter.jpg') }}"
            class="card-image">

            <div class="card-content">

                <div class="info-top">
                    <h3>Dokter Profesional</h3>
                    <span>+</span>
                </div>

                <div class="info-detail">
                    Ditangani langsung oleh dokter dan tenaga medis
                    berpengalaman dengan standar pelayanan modern.
                </div>

            </div>

        </div>

        <!-- CARD 3 -->
        <div class="info-card" onclick="toggleCard(this)">

            <img src="{{ asset('images/fasilitas.jpg') }}"
            class="card-image">

            <div class="card-content">

                <div class="info-top">
                    <h3>Fasilitas Modern</h3>
                    <span>+</span>
                </div>

                <div class="info-detail">
                    Menggunakan alat modern dan ruangan nyaman
                    untuk mendukung keamanan dan kenyamanan pasien.
                </div>

            </div>

        </div>

        <!-- CARD 4 -->
        <div class="info-card" onclick="toggleCard(this)">

            <img src="{{ asset('images/cepat.jpg') }}"
            class="card-image">

            <div class="card-content">

                <div class="info-top">
                    <h3>Proses Cepat</h3>
                    <span>+</span>
                </div>

                <div class="info-detail">
                    Metode modern membuat proses tindakan
                    lebih cepat dan minim rasa sakit.
                </div>

            </div>

        </div>

        <!-- CARD 5 -->
        <div class="info-card" onclick="toggleCard(this)">

            <img src="{{ asset('images/konsultasi.jpg') }}"
            class="card-image">

            <div class="card-content">

                <div class="info-top">
                    <h3>Konsultasi Gratis</h3>
                    <span>+</span>
                </div>

                <div class="info-detail">
                    Pasien dapat berkonsultasi terlebih dahulu
                    untuk menentukan metode terbaik sesuai kebutuhan.
                </div>

            </div>

        </div>

    </div>

</section>

<!-- METODE -->
<section class="section" id="metode">

    <h2>Metode Sunat Modern</h2>

    <p class="section-desc">
        Kami menyediakan berbagai metode sunat modern
        yang aman, nyaman, dan cepat penyembuhannya.
    </p>

    <div class="metode-grid">

        <!-- METODE 1 -->
        <a href="/metode/laser" class="metode-card">

            <img src="{{ asset('images/laser1.png') }}">

            <div class="metode-content">

                <h3>Metode Laser</h3>

                <p>
                    Metode modern dengan proses cepat
                    dan minim pendarahan.
                </p>

            </div>

        </a>

        <!-- METODE 2 -->
        <a href="/metode/clamp" class="metode-card">

            <img src="{{ asset('images/clamp.jpeg') }}">

            <div class="metode-content">

                <h3>Metode Clamp</h3>

                <p>
                    Tanpa jahitan dan nyaman untuk
                    anak maupun dewasa.
                </p>

            </div>

        </a>

        <!-- METODE 3 -->
        <a href="/metode/stapler" class="metode-card">

            <img src="{{ asset('images/stapler.jpg') }}">

            <div class="metode-content">

                <h3>Metode Stapler</h3>

                <p>
                    Penyembuhan lebih cepat
                    dengan alat modern.
                </p>

            </div>

        </a>

        <!-- METODE 4 -->
        <a href="/metode/konvensional" class="metode-card">

            <img src="{{ asset('images/konvensional.jpg') }}">

            <div class="metode-content">

                <h3>Metode Konvensional</h3>

                <p>
                    Metode klasik dengan hasil rapi
                    dan biaya lebih terjangkau.
                </p>

            </div>

        </a>

    </div>

</section>

@include('components.footer')

<script>

/* HERO SLIDER */
let slides = document.querySelectorAll('.slide');
let index = 0;

setInterval(() => {

    slides[index].classList.remove('active');

    index = (index + 1) % slides.length;

    slides[index].classList.add('active');

}, 4000);

/* HEADER */
window.addEventListener("scroll", function(){

    let header = document.querySelector(".header");

    if(window.scrollY > 50){
        header.style.background = "#000";
    } else {
        header.style.background = "rgba(0,0,0,0.4)";
    }

});

/* CARD */
function toggleCard(card){

    let allCards = document.querySelectorAll('.info-card');

    allCards.forEach(item => {

        if(item !== card){
            item.classList.remove('active');
        }

    });

    card.classList.toggle('active');
}

</script>

</body>
</html>