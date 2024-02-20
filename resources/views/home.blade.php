@extends('layout')

@section('content')


<div class="container-fluid my-4">

    <div class="d-flex my-3">
        <div class="dropdown me-3">
            <a class="btn btn-secondary dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                Sort
            </a>

            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Name</a></li>
                <li><a class="dropdown-item" href="#">Date Added</a></li>
                <li><a class="dropdown-item" href="#">Status</a></li>
            </ul>
        </div>

    </div>



    <div class="row">
        <div class="col-lg-3 mb-3 mb-lg-0">
            <div class="card" style="">
                <img src="{{asset('img/Untitled-1 cov.png')}}" class="card-img-top" alt="..."
                    style="height: 200px;object-fit:cover;">

                <div class="doptions">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <img class="img-fluid" src="img/settings2.png">
                        </button>
                        <ul class="dropdown-menu dropdown-menu-lg-end">
                            <li><a class="dropdown-item" aria-current="page" href="#" data-bs-toggle="modal"
                                    data-bs-target="#editwebsite">Edit</a></li>
                        </ul>
                    </div>
                </div>

                <div class="card-body">
                    <h5 class="card-title">Title</h5>
                    <p class="card-text">
                        <a href="http://127.0.0.1:8000">http://127.0.0.1:8000 </a><br>
                        card's
                        content.
                    </p>
                    <a href="#" class="btn btn-primary">Go somewhere</a>
                </div>
            </div>
        </div>

    </div>


</div>





@endsection