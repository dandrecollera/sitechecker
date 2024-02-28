@extends('layout')

@section('content')
    <div class="container-fluid my-4">
        @php
            $reports = DB::table('cronreport')->orderBy('id', 'desc')->limit(10)->get()->toArray();
            $lastreport = DB::table('cronreport')->orderBy('id', 'desc')->first();
        @endphp

        <code>
            <pre>
Displaying lat 10 reports (generated {{ $lastreport->created_at }}): <a href="javascript:location.reload(true)">Refresh Page</a>

@foreach ($reports as $report)
{{ \Carbon\Carbon::parse($report->created_at)->format('F j, Y, g:i A') }} Report
{!! $report->report !!}
@endforeach

            </pre>

        </code>
    </div>

    <div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="exampleModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
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

    <div class="modal fade" id="forcemodal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
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
