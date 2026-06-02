<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display:none;">
        @csrf
    </form>

    ini dashboard penyewa <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
</body>

</html>