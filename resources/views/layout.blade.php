<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Site Checker</title>

    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <script src="https://kit.fontawesome.com/55faa7e024.js" crossorigin="anonymous"></script>

    <style>
        img.card-img-top.dcc-image {
            height: 180px;
            object-fit: cover;
            border-bottom: 1px solid #ccc;
        }

        .doptions img.img-fluid {
            margin-top: -3px;
        }

        .doptions {
            position: absolute;
            right: 0px;
        }

        pre {
            background-color: #000 !important;
            color: #fff !important;
            padding: 20px !important;
        }

        ul.dcclist {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        ul.dcclist li {
            float: left;
            padding-right: 10px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Site Checker</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="modal" data-bs-target="#exampleModal"
                            style="cursor: pointer">Add Website</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Report</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" aria-disabled="true">Force</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>

    @stack('jsscripts')

</body>

</html>