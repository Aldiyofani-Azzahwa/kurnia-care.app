<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Register | Kurnia Care</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

body{
    min-height:100vh;

    display:flex;
    justify-content:center;
    align-items:center;

    background:
    radial-gradient(circle at top right,#86efac 0%,#d9f7e2 25%),
    linear-gradient(to bottom left,#f0fff4,#dcfce7);

    overflow:hidden;

    padding:20px;
}

/* CARD */
.card{

    width:100%;
    max-width:430px;

    background:rgba(255,255,255,0.7);

    backdrop-filter:blur(15px);

    border-radius:28px;

    padding:40px;

    border:1px solid rgba(255,255,255,0.4);

    box-shadow:
    0 20px 50px rgba(0,0,0,0.08);
}

/* TITLE */
.title{
    text-align:center;
    margin-bottom:30px;
}

.title h1{
    font-size:38px;
    color:#166534;
    margin-bottom:10px;
    font-weight:800;
}

.title p{
    color:#4b5563;
}

/* INPUT */
.input-group{
    margin-bottom:20px;
}

.input-group label{
    display:block;
    margin-bottom:10px;
    color:#14532d;
    font-weight:600;
}

.input-group input{

    width:100%;

    padding:15px;

    border:none;
    outline:none;

    border-radius:16px;

    background:white;

    border:1px solid rgba(0,0,0,0.05);

    font-size:14px;
}

/* BUTTON */
.btn{

    width:100%;

    padding:15px;

    border:none;

    border-radius:16px;

    background:
    linear-gradient(
        135deg,
        #22c55e,
        #16a34a
    );

    color:white;

    font-size:15px;
    font-weight:600;

    cursor:pointer;

    margin-top:10px;

    transition:0.3s;

    box-shadow:
    0 10px 25px rgba(34,197,94,0.25);
}

.btn:hover{
    transform:translateY(-3px);

    box-shadow:
    0 15px 35px rgba(34,197,94,0.35);
}

/* BOTTOM */
.bottom-text{
    margin-top:25px;
    text-align:center;
    color:#4b5563;
}

.bottom-text a{
    color:#16a34a;
    text-decoration:none;
    font-weight:600;
}

/* MOBILE */
@media(max-width:768px){

    .card{
        padding:30px;
        border-radius:24px;
    }

    .title h1{
        font-size:32px;
    }

}

</style>
</head>

<body>

<div class="card">

    <div class="title">

        <h1>Kurnia Care</h1>

        <p>Buat akun baru</p>

    </div>

    <form action="/register" method="POST">

        @csrf

        <div class="input-group">

            <label>Nama</label>

            <input
            type="text"
            name="name"
            required>

        </div>

        <div class="input-group">

            <label>Email</label>

            <input
            type="email"
            name="email"
            required>

        </div>

        <div class="input-group">

            <label>Password</label>

            <input
            type="password"
            name="password"
            required>

        </div>

        <button class="btn">
            Register
        </button>

    </form>

    <div class="bottom-text">

        Sudah punya akun?

        <a href="/login">
            Login
        </a>

    </div>

</div>

</body>
</html>