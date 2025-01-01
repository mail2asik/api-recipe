@extends('admin.admin_template')

@section('content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ $page_title }}</h1>

    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Basic Data</h6>
                </div>
                <div class="card-body">
                    @include('admin.section.flash_message')
                    <form autocomplete="off" class="form-validation" id="profile_form" role="form" method="post" action="{{ secure_url('/profile') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <div class="form-group">
                            <label for="pwd">Name</label>
                            <input type="text" placeholder="name" name="name" class="form-control" value="{{ $user->name  }}" />
                        </div>
                        <div class="text-center  m-t-20">
                            <button type="submit" id="submitBtn" class="btn btn-embossed btn-primary" data-style="expand-left">Submit</button>
                            <a href=" {{ secure_url('/') }}">
                                <button type="button" class="cancel btn btn-embossed btn-default m-b-10 m-r-0">Cancel</button>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <!-- jQuery validation v1.14.0 -->
    <script src="{{ secure_asset("admin/vendor/jquery-validation/jquery.validate.min.js") }}"></script>
    <!-- jQuery validation v1.14.0 additional methods -->
    <script src="{{ secure_asset("admin/vendor/jquery-validation/additional-methods.min.js") }}"></script>
    <script>
        $(document).ready(function(){

            var form = $("#profile_form");
            $('#submitBtn').click(function(e) {
                form.validate({
                    errorClass: 'validationError',
                    rules: {
                        name: {
                            required: true
                        }
                    },
                    messages: {
                        name: {
                            required: "Please enter name"
                        }
                    },
                    errorPlacement: function(error, element) {
                        error.insertAfter(element);
                    }
                });
                e.preventDefault();
                if (form.valid()) {
                    $(this).addClass('ladda-button');
                    var l = Ladda.create(this);
                    l.start();

                    $(this).attr('disabled','disabled');
                    form.submit();
                }
            });
        });
    </script>
@endsection
