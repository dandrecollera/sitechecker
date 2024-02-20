@extends('layout')

@section('content')


<div class="container-fluid my-4">

    <div class="d-flex my-3">
        <div class="dropdown me-3">
            <a class="btn btn-secondary dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                Sort
            </a>
            <a class="btn btn-secondary">
                <i class="fa-solid fa-arrow-down-wide-short"></i>
            </a>

            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Name</a></li>
                <li><a class="dropdown-item" href="#">Date Added</a></li>
                <li><a class="dropdown-item" href="#">Status</a></li>
            </ul>
        </div>

    </div>



    <div class="row">
        @foreach ($sites as $site)

        <div class="col-lg-3 mb-3 mb-lg-3">
            <div class="card" style="">
                <img src="{{asset($site->screenshot)}}" class="card-img-top" alt="..."
                    style="height: 250px;object-fit:cover;">

                <div class="doptions">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="fa-solid fa-gear"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-lg-end">
                            <li><a class="dropdown-item" aria-current="page" href="#" data-bs-toggle="modal"
                                    data-bs-target="#editwebsite">Edit</a></li>
                            <li><a class="dropdown-item" aria-current="page" href="#" data-bs-toggle="modal"
                                    data-bs-target="#editwebsite">Delete</a></li>
                        </ul>
                    </div>
                </div>

                <div class="card-body">
                    <h5 class="card-title">{{$site->name}}</h5>
                    <p class="card-text">
                        <a href="{{$site->url}}" target="_empty">{{$site->url}}</a><br>
                        card's
                        content.
                    </p>
                    <div class="d-flex mt-4 mb-3">
                        <i class="fa-solid fa-circle me-3 fa-lg" style="{{$site->tracking == " 1" ? 'color:green'
                            : 'color:red' }}"></i>
                        <i class="fa-solid fa-image me-3 fa-lg" style="color:rgb(0, 0, 0)"></i>
                        <i class="fa-solid fa-eraser me-3 fa-lg" style="color:rgb(0, 0, 0)"></i>

                        @if ($site->wordpress_active == 1)
                        <i class="fa-brands fa-wordpress me-3 fa-lg" style="color:rgb(0, 133, 173)"></i>
                        @endif
                    </div>
                </div>
                <div class="card-footer text-white {{$site->active == " 0" ? 'bg-danger' : 'bg-success' }}">
                    {{$site->active == "0" ? "Offline" : "Success"}}
                </div>
            </div>
        </div>


        @endforeach
    </div>


</div>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add Website</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <form action="url_add" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="webtitle" class="form-label">Website Title (ex: SampleSite)</label>
                        <input type="text" class="form-control" id="webtitle" placeholder="Title" name="webtitle">
                    </div>
                    <div class="mb-3">
                        <label for="weburl" class="form-label">Website URL (ex: https://samplesite.com)</label>
                        <input type="text" class="form-control" id="weburl" placeholder="Web URL" name="weburl">
                    </div>

                    WordPress Site<br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="wordpress" id="inlineRadio1" value="1"
                            checked>
                        <label class="form-check-label" for="inlineRadio1">Yes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="wordpress" id="inlineRadio2" value="0">
                        <label class="form-check-label" for="inlineRadio2">No</label>
                    </div>

                    <br>

                    <div class="float-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>



@endsection