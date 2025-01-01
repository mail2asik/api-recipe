@extends('admin.auth_template')

@section('content')
    <div class="row">
        @include('admin.section.flash_message')
        <div class="col-sm-6 col-md-4 col-md-offset-4">
            <div class="account-wall" style="background-color: rgba(255, 255, 255, 0);">
                <i class="user-img icons-faces-users-03"></i>
                <form class="form-signin" autocomplete="off" id="reset_password_form" class="form-horizontal" role="form" method="post" action="{{ secure_url('/doResetPassword') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="hidden" name="user_uid" value="{{ $user_uid }}" />
                    <input type="hidden" name="token" value="{{ $token }}" />
                    <div class="append-icon m-b-20">
                        <input name="password" id="password" class="form-control form-white password" placeholder="Password" style="border-top-right-radius: 2px; border-top-left-radius: 2px;" type="password" required>
                        <i class="icon-lock"></i>
                    </div>
                    <div class="append-icon m-b-20">
                        <input name="confirm_password" id="confirm_password" class="form-control form-white password" placeholder="Confirm Password" style="border-top-right-radius: 2px; border-top-left-radius: 2px;" type="password" required>
                        <i class="icon-lock"></i>
                    </div>
                    <button type="submit" id="submitBtn" class="btn btn-lg btn-danger btn-block" data-style="expand-left">Submit</button>
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

        var form = $("#reset_password_form");
        $('#submitBtn').click(function(e) {
            form.validate({
                rules: {
                    password: {
                        required: true,
                        minlength: 6
                    },
                    confirm_password: {
                        required: true,
                        minlength: 6,
                        equalTo: "#password"
                    }
                },
                messages: {
                    password: {
                        required: "Please enter password",
                        minlength: jQuery.validator.format("Enter at least {0} characters")
                    },
                    confirm_password: {
                        required: "Please enter re-password",
                        minlength: jQuery.validator.format("Enter at least {0} characters"),
                        equalTo: "Enter the same password as above"
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
