<header class="header">
    <div class="logo">Kurnia Care</div>

    <nav>
        <a href="/">Home</a>
        <a href="#about">Tentang</a>
        <a href="#contact">Kontak</a>
        <a href="/pasien" class="btn-nav">Login</a>
    </nav>
</header>

<style>
.header {
    position: fixed;
    top: 0;
    width: 100%;
    padding: 20px 10%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    z-index: 999;
    background: rgba(0,0,0,0.4);
    backdrop-filter: blur(10px);
    transition: 0.3s;
}

.logo {
    font-weight: 800;
    font-size: 20px;
}

.header nav {
    display: flex;
    gap: 25px;
}

.header a {
    color: white;
    text-decoration: none;
    transition: 0.3s;
}

.header a:hover {
    color: #00c896;
}

.btn-nav {
    color: #00c896;
}
</style>