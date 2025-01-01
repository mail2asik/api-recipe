@extends('admin.auth_template')

@section('content')
    <div class="row">
        @include('admin.section.flash_message')
        <div class="col-sm-6 col-md-4 col-md-offset-4">
            <div class="account-wall" style="background-color: rgba(255, 255, 255, 0);">
                <i class="user-img icons-faces-users-03"></i>
                <form class="form-signin" autocomplete="off" id="fogot_password_form" class="form-horizontal" role="form" method="post" action="{{ secure_url('/doForgotPassword') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <div class="append-icon">
                        <input name="email" id="email" class="form-control form-white username" placeholder="Email" style="border-bottom-right-radius: 2px; border-bottom-left-radius: 2px; margin-bottom: 8px;" type="text" value="" required>
                        <i class="icon-user"></i>
                    </div>
                    <button type="submit" id="submitBtn" class="btn btn-lg btn-danger btn-block" data-style="expand-left">Submit</button>
                    <div class="clearfix">
                        <p class="pull-right m-t-20"><a id="password" href="{{ secure_url('login') }}">Login?</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
        <!-- jQuery validation v1.14.0 -->
<script src="{{ secure_asset("assets/global/plugins/jquery-validation/jquery.validate.min.js") }}"></script>
<!-- jQuery validation v1.14.0 additional methods -->
<script src="{{ secure_asset("assets/global/plugins/jquery-validation/additional-methods.min.js") }}"></script>
<script>
    $(document).ready(function(){

        var form = $("#fogot_password_form");
        $('#submitBtn').click(function(e) {
            form.validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    }
                },
                messages: {
                    email: {
                        required: "Please enter a email address",
                        email: "Enter a valid email address."
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
