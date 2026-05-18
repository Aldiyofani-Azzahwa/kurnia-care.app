<header class="header">

    <div class="logo">Kurnia Care</div>

    <nav id="nav">
        <a href="/">Home</a>
        <a href="#about">Tentang Kami</a>
        <a href="#contact">Kontak</a>
        <a href="/login" class="btn-nav">Login</a>
    </nav>

    <!-- HAMBURGER -->
    <div class="menu-toggle" id="menu-toggle">☰</div>

</header>

<style>
.header {
    position: fixed;
    top: 0;
    width: 100%;
    padding: 20px 5%;
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

/* NAV DESKTOP */
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

/* HAMBURGER  */
.menu-toggle {
    display: none;
    font-size: 24px;
    cursor: pointer;
}


/* MOBILE */

@media (max-width: 768px) {

    .menu-toggle {
        display: block;
    }

    .header nav {
        position: absolute;
        top: 70px;
        right: 0;
        width: 100%;
        background: black;
        flex-direction: column;
        align-items: center;
        display: none;
        padding: 20px 0;
    }

    .header nav.active {
        display: flex;
    }

    .header nav a {
        padding: 10px 0;
    }
}
</style>

<script>
const toggle = document.getElementById('menu-toggle');
const nav = document.getElementById('nav');

toggle.onclick = () => {
    nav.classList.toggle('active');
};
</script>