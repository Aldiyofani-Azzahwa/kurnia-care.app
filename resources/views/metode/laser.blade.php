<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Metode Laser | Kurnia Care</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

body{
    background:
    radial-gradient(circle at top left, #12372A 0%, #0b1814 40%),
    linear-gradient(to bottom, #08110e, #050807);

    color:white;
    overflow-x:hidden;
}

/* HERO */
.hero{
    padding:140px 5% 80px;
}

.hero-image{
    position:relative;
    overflow:hidden;
    border-radius:28px;
}

.hero-image img{
    width:100%;
    height:500px;
    object-fit:cover;
    display:block;
    filter:brightness(0.7);
    transition:0.5s;
}

.hero-image:hover img{
    transform:scale(1.03);
}

.hero-overlay{
    position:absolute;
    inset:0;
    background:linear-gradient(
        to top,
        rgba(0,0,0,0.75),
        rgba(0,0,0,0.15)
    );
}

.hero-content{
    position:absolute;
    left:50px;
    bottom:50px;
    z-index:2;
    max-width:800px;
}

.hero-content h1{
    font-size:clamp(35px,6vw,70px);
    font-weight:800;
    margin-bottom:20px;
}

.hero-content p{
    color:#d3e5da;
    line-height:1.9;
    font-size:16px;
}

/* SECTION */
.section{
    padding:0 5% 90px;
}

/* GRID */
.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(320px,1fr));
    gap:25px;
}

/* BOX */
.box{
    background:rgba(17,25,22,0.8);
    backdrop-filter:blur(12px);

    border-radius:24px;
    padding:35px;

    border:1px solid rgba(255,255,255,0.06);

    transition:0.3s;
}

.box:hover{
    transform:translateY(-8px);
    border-color:#4ade80;

    box-shadow:
    0 10px 30px rgba(74,222,128,0.12);
}

.box h2{
    margin-bottom:25px;
    color:#86efac;
    font-size:28px;
}

/* LIST */
.box ul{
    list-style:none;
}

.box li{
    position:relative;
    padding-left:28px;
    margin-bottom:18px;

    color:#d0ddd5;
    line-height:1.9;
}

.box li::before{
    content:"✓";
    position:absolute;
    left:0;
    top:0;

    color:#4ade80;
    font-weight:bold;
}

/* BAD LIST */
.kekurangan li::before{
    content:"✕";
    color:#f87171;
}

/* INFO */
.info{
    margin-top:35px;

    background:rgba(17,25,22,0.75);

    border:1px solid rgba(255,255,255,0.05);

    border-radius:24px;
    padding:35px;

    backdrop-filter:blur(12px);
}

.info h2{
    margin-bottom:20px;
    font-size:30px;
    color:#ecfdf5;
}

.info p{
    color:#c9d7cf;
    line-height:2;
}

/* BUTTON */
.btn-back{
    display:inline-flex;
    align-items:center;
    gap:10px;

    margin-top:35px;

    padding:14px 24px;

    background:linear-gradient(
        135deg,
        #22c55e,
        #16a34a
    );

    color:white;
    text-decoration:none;

    border-radius:14px;

    transition:0.3s;

    box-shadow:
    0 10px 30px rgba(34,197,94,0.25);
}

.btn-back:hover{
    transform:translateY(-4px);

    box-shadow:
    0 15px 35px rgba(34,197,94,0.35);
}

/* RESPONSIVE */
@media(max-width:768px){

    .hero{
        padding:120px 20px 60px;
    }

    .section{
        padding:0 20px 70px;
    }

    .hero-image img{
        height:300px;
    }

    .hero-content{
        left:25px;
        right:25px;
        bottom:25px;
    }

    .hero-content h1{
        font-size:38px;
    }

    .hero-content p{
        font-size:14px;
        line-height:1.8;
    }

    .box{
        padding:28px;
    }

    .info{
        padding:28px;
    }

}

</style>
</head>

<body>

@include('components.header')

<!-- HERO -->
<section class="hero">

    <div class="hero-image">

        <img src="{{ asset('images/laser.jpg') }}">

        <div class="hero-overlay"></div>

        <div class="hero-content">

            <h1>Metode Laser</h1>

            <p>
                Metode laser merupakan metode sunat modern yang menggunakan
                alat khusus untuk membantu proses pemotongan lebih cepat,
                minim pendarahan, dan memberikan kenyamanan lebih bagi pasien.
            </p>

        </div>

    </div>

</section>

<!-- CONTENT -->
<section class="section">

    <div class="grid">

        <!-- KEUNTUNGAN -->
        <div class="box">

            <h2>Keuntungan</h2>

            <ul>
                <li>Proses tindakan lebih cepat</li>
                <li>Minim pendarahan</li>
                <li>Penyembuhan lebih cepat</li>
                <li>Hasil lebih modern dan rapi</li>
                <li>Nyaman untuk pasien anak maupun dewasa</li>
            </ul>

        </div>

        <!-- KEKURANGAN -->
        <div class="box kekurangan">

            <h2>Kekurangan</h2>

            <ul>
                <li>Biaya lebih mahal dibanding metode biasa</li>
                <li>Memerlukan alat khusus</li>
                <li>Tidak semua kondisi pasien cocok</li>
                <li>Harus dilakukan tenaga profesional</li>
            </ul>

        </div>

    </div>

    <!-- PENJELASAN -->
    <div class="info">

        <h2>Tentang Metode Laser</h2>

        <p>
            Metode laser menjadi salah satu metode sunat modern yang cukup populer
            karena prosesnya cepat dan minim pendarahan. Teknologi ini membantu
            proses tindakan menjadi lebih efisien serta mempercepat pemulihan pasien.
            <br><br>
            Selain itu, metode laser juga memberikan hasil yang lebih rapi dan nyaman
            terutama untuk anak-anak maupun dewasa yang ingin proses tindakan modern
            dengan rasa nyaman yang lebih baik.
        </p>

        <a href="/" class="btn-back">
            ← Kembali ke Beranda
        </a>

    </div>

</section>

@include('components.footer')

</body>
</html>