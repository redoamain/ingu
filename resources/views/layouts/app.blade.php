<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'INGU Inventory')</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }

        /* Navbar sederhana */
        .navbar {
            background-color: #000080;
            padding: 10px;
            border-bottom: 2px solid #c0c0c0;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            font-weight: bold;
            margin: 0 10px;
        }

        .navbar a:hover {
            color: #ffff00;
            text-decoration: underline;
        }

        .logo {
            font-size: 20px;
            font-weight: bold;
            color: white;
        }

        /* Container */
        .container {
            margin: 20px auto;
            width: 90%;
            max-width: 1200px;
            min-height: 500px;
        }

        /* Card Style ala 2000an */
        .card {
            background-color: #ffffff;
            border: 1px solid #c0c0c0;
            padding: 10px;
            margin: 10px;
            width: 220px;
            display: inline-block;
            vertical-align: top;
        }

        .card-image {
            background-color: #f0f0f0;
            border: 1px solid #808080;
            text-align: center;
            margin-bottom: 10px;
            height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-image img {
            max-width: 100%;
            max-height: 140px;
        }

        .card-title {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 5px;
            color: #000080;
        }

        .card-itemid {
            font-size: 11px;
            color: #666666;
            margin-bottom: 5px;
        }

        .card-detail {
            font-size: 11px;
            color: #333333;
            margin-bottom: 10px;
        }

        .card-button {
            background-color: #c0c0c0;
            border: 1px solid #808080;
            padding: 5px;
            text-align: center;
            font-size: 12px;
            text-decoration: none;
            color: #000000;
            display: block;
        }

        .card-button:hover {
            background-color: #e0e0e0;
        }

        /* Search box sederhana */
        .search-box {
            background-color: #f0f0f0;
            padding: 10px;
            border: 1px solid #808080;
            margin-bottom: 20px;
            text-align: center;
        }

        input[type="text"] {
            border: 1px solid #808080;
            padding: 5px;
            font-family: 'Times New Roman', Times, serif;
            font-size: 12px;
        }

        .btn {
            background-color: #c0c0c0;
            border: 1px solid #808080;
            padding: 5px 15px;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            cursor: pointer;
            text-decoration: none;
            color: #000000;
            display: inline-block;
        }

        .btn:hover {
            background-color: #e0e0e0;
        }

        /* Pagination sederhana */
        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination a, .pagination span {
            background-color: #c0c0c0;
            border: 1px solid #808080;
            padding: 4px 8px;
            margin: 0 2px;
            text-decoration: none;
            color: #000000;
            font-size: 11px;
            display: inline-block;
        }

        .pagination a:hover {
            background-color: #e0e0e0;
        }

        .pagination .active {
            background-color: #808080;
            color: white;
            font-weight: bold;
        }

        /* Footer sederhana */
        .footer {
            background-color: #c0c0c0;
            padding: 10px;
            text-align: center;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            border-top: 1px solid #ffffff;
            margin-top: 20px;
        }

        hr {
            border: 1px solid #c0c0c0;
        }

        .clear {
            clear: both;
        }
    </style>
</head>
<body>
    <!-- Navbar sederhana -->
    <div class="navbar">
        <span class="logo">🏠 INGU</span>
        <a href="{{ route('products.index') }}">Home</a>
        <a href="/admin/login">Login</a>
    </div>

    <!-- Content -->
    <div class="container">
        @yield('content')
    </div>

    <!-- Footer sederhana -->
    <div class="footer">
        &copy; 2000 - {{ date('Y') }} citiplumb<br>
        <font size="1">Best viewed with Internet Explorer 6.0+</font>
    </div>
</body>
</html>
