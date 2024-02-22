@extends('layout')

@section('content')
    <div class="container-fluid my-4">

        @isset($err)
            <div class="alert alert-{{ $errors[$err][1] }}" role="alert">
                {{ $errors[$err][0] }}
            </div>
        @endisset

        @isset($nt)
            <div class="alert alert-{{ $notifs[$nt][1] }}" role="alert">
                {{ $notifs[$nt][0] }}
            </div>
        @endisset

        <div class="d-flex my-3">
            <div class="dropdown me-3">
                <a aria-expanded="false" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button">
                    Sort: {{ $or == 0 ? 'Default' : $order[$or][0] }}
                </a>

                @if ($srt == 'asc')
                    <a class="btn btn-secondary" href="/?or={{ $or }}&srt=desc">
                        <i class="fa-solid fa-arrow-down-wide-short"></i>
                        {{-- <i class="fa-solid fa-arrow-up-short-wide"></i> --}}
                    </a>
                @else
                    <a class="btn btn-secondary" href="/?or={{ $or }}&srt=asc">
                        <i class="fa-solid fa-arrow-up-short-wide"></i>
                    </a>
                @endif


                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="/?or=0&srt={{ $srt }}">Default</a></li>
                    <li><a class="dropdown-item" href="/?or=1&srt={{ $srt }}">Name</a></li>
                    <li><a class="dropdown-item" href="/?or=2&srt={{ $srt }}">Date Added</a></li>
                    <li><a class="dropdown-item" href="/?or=3&srt={{ $srt }}">Status</a></li>
                </ul>
            </div>

        </div>

        <div class="row">
            @foreach ($sites as $site)
                <div class="col-lg-3 mb-3 mb-lg-3">
                    <div class="card" style="">
                        <img alt="..." class="card-img-top" src="{{ asset($site->screenshot) }}" style="height: 250px;object-fit:cover;">

                        <div class="doptions">
                            <div class="dropdown">
                                <button aria-expanded="false" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" type="button">
                                    <i class="fa-solid fa-gear"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-lg-end">
                                    <li>
                                        <a aria-current="page" class="dropdown-item axu-edit" data-bs-target="#editwebsite" data-bs-toggle="modal" data-id="{{ $site->id }}" data-title="{{ $site->name }}" data-url="{{ $site->url }}" data-wordpress="{{ $site->wordpress_active }}" href="#">Edit</a>
                                    </li>
                                    <li>
                                        <a aria-current="page" class="dropdown-item axu-delete" data-bs-target="#deletesite" data-bs-toggle="modal" data-id="{{ $site->id }}" data-title="{{ $site->name }}" data-url="{{ $site->url }}" href="#">Delete</a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="card-body">
                            <h5 class="card-title">{{ $site->name }}</h5>
                            <p class="card-text">
                                <a href="{{ $site->url }}" target="_empty">{{ $site->url }}</a><br>
                                card's
                                content.
                            </p>
                            <div class="d-flex ">

                                <a href="">
                                    <i class="fa-solid fa-circle me-3 fa-lg" style="{{ $site->tracking == ' 1' ? 'color:green' : 'color:red' }}"></i>
                                </a>


                                <a href="">
                                    <i class="fa-solid fa-image me-3 fa-lg" style="color:rgb(0, 0, 0)"></i>

                                </a>
                                <a href="">
                                    <i class="fa-solid fa-eraser me-3 fa-lg" style="color:rgb(0, 0, 0)"></i>
                                </a>

                                @if ($site->wordpress_active == 1)
                                    <a href="{{ $site->url . 'wp-admin' }}" target="_blank">
                                        <i class="fa-brands fa-wordpress me-3 fa-lg" style="color:rgb(0, 133, 173)"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer text-white {{ $site->active == ' 0' ? 'bg-danger' : 'bg-success' }}">
                            {{ $site->active == '0' ? 'Offline' : 'Online' }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>


    </div>

    <div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="exampleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add Website</h1>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
                </div>
                <div class="modal-body">

                    <form action="url_add" method="post">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="webtitle">Website Title (ex: SampleSite)</label>
                            <input class="form-control" id="webtitle" name="webtitle" placeholder="Title" type="text">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="weburl">Website URL (ex: https://samplesite.com)</label>
                            <input class="form-control" id="weburl" name="weburl" placeholder="Web URL" type="text">
                        </div>

                        WordPress Site<br>
                        <div class="form-check form-check-inline">
                            <input checked class="form-check-input" id="inlineRadio1" name="wordpress" type="radio" value="1">
                            <label class="form-check-label" for="inlineRadio1">Yes</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" id="inlineRadio2" name="wordpress" type="radio" value="0">
                            <label class="form-check-label" for="inlineRadio2">No</label>
                        </div>

                        <br>

                        <div class="float-end">
                            <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cancel</button>
                            <button class="btn btn-primary" type="submit">Add</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="editwebsite" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Website</h1>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
                </div>
                <div class="modal-body">

                    <form action="url_edit" method="post">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="webtitle">Website Title (ex: SampleSite)</label>
                            <input class="form-control" id="webtitle-edit" name="webtitle" placeholder="Title" type="text">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="weburl">Website URL (ex: https://samplesite.com)</label>
                            <input class="form-control" id="weburl-edit" name="weburl" placeholder="Web URL" type="text">
                        </div>

                        WordPress Site<br>
                        <div class="form-check form-check-inline">
                            <input checked class="form-check-input" id="wp-edit1" name="wordpress" type="radio" value="1">
                            <label class="form-check-label" for="inlineRadio1">Yes</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" id="wp-edit2" name="wordpress" type="radio" value="0">
                            <label class="form-check-label" for="inlineRadio2">No</label>
                        </div>

                        <br>

                        <input id="edit-id" name="edit-id" type="hidden">

                        <div class="float-end">
                            <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cancel</button>
                            <button class="btn btn-primary" type="submit">Edit</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="deletesite" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Website</h1>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete the site from the list?<br>
                    <b><span id="titledel">Title</span></b><br>
                    <b><span id="urldel">URL</span></b><br>
                    <br>
                    <div class="float-end">
                        <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cancel</button>
                        <a class="btn btn-danger" href="" id="delbutton">Delete</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="forcemodal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Force Fetch</h1>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
                </div>
                <div class="modal-body">
                    Force Fetch?<br>
                    <br>
                    <div class="float-end">
                        <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cancel</button>
                        <a class="btn btn-danger" href="forceFetch" id="delbutton">Fetch</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('jsscripts')
    <script>
        $(document).ready(function() {
            $('.axu-edit').on('click', function() {
                console.log($(this).data('id'));
                $('#webtitle-edit').val($(this).data('title'));
                $('#weburl-edit').val($(this).data('url'));
                $('#edit-id').val($(this).data('id'));

                if ($(this).data('wordpress') == 1) {
                    $('#wp-edit1').prop('checked', true);
                    $('#wp-edit2').prop('checked', false);
                } else {
                    $('#wp-edit1').prop('checked', false);
                    $('#wp-edit2').prop('checked', true);
                }
            })

            $('.axu-delete').on('click', function() {
                $('#titledel').html($(this).data('title'));
                $('#urldel').html($(this).data('url'));

                $('#delbutton').attr('href', `/url_del?id=${$(this).data('id')}`);
            })
        })
    </script>
@endpush
