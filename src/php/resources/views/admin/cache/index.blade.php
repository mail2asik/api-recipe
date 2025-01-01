@extends('admin.admin_template')

@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ $page_title }}</h6>
                </div>
                <div class="card-body">
                    @include('admin.section.flash_message')
                    <a href=" {{ secure_url('/cache/clear') }}">
                        <button type="button" class="cancel btn btn-danger btn-default">Clear Cache</button>
                    </a>
                    <br/><br/>
                    <blockquote>
                        <strong>Caution:</strong><br/>
                        Clear the cache whenever required. (eg) After uploading system level updates, do clear cache (One time) to reflect the changes immediately.<br />
                        Do not clear multiple times unnecessarily, it will degrade overall site performance.
                    </blockquote>
                </div>
            </div>
        </div>
    </div>

@endsection
