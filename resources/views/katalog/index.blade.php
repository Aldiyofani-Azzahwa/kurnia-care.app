<!DOCTYPE html>
<html>
<head>
    <title>Katalog Produk</title>
</head>
<body>

<h1>Katalog Produk</h1>

<ul>
@foreach($produk as $index => $item)
    <li>
        <a href="/katalog/{{ $index }}">
            {{ $item['nama'] }} - Rp {{ number_format($item['harga']) }}
        </a>
    </li>
@endforeach
</ul>

</body>
</html>