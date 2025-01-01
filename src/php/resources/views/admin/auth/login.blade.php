@extends('admin.auth_template')

@section('content')
    <div class="row">
        <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
        <div class="col-lg-6">
            <div class="p-5">
                <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Welcome Admin!</h1>
                </div>
                @include('admin.section.flash_message')
                <form class="form-signin" role="form" id="login_form" method="post" action="{{ secure_url('/doLogin') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <input type="email" name="email" class="form-control form-control-user" aria-describedby="emailHelp" placeholder="Enter Email Address..." value="{{ old('email') }}" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control form-control-user" placeholder="Password" required>
                    </div>
                    <div class="form-group d-none">
                        <div class="custom-control custom-checkbox small">
                            <input type="checkbox" class="custom-control-input" id="customCheck">
                            <label class="custom-control-label" for="customCheck">Remember Me</label>
                        </div>
                    </div>
                    <button type="submit" id="submitBtn" class="btn btn-primary btn-user btn-block" data-style="expand-left">Login</button>
                </form>
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

            var form = $("#login_form");
            $('#submitBtn').click(function(e) {
                form.validate({
                    errorClass: 'validationError',
                    rules: {
                        email: {
                            required: true,
                            email: true
                        },
                        password: {
                            required: true,
                            minlength: 6
                        }
                    },
                    messages: {
                        email: {
                            required: "Enter a email",
                            email: "Enter a valid email address."
                        },
                        password: {
                            required: "Enter a password",
                            minlength: "Enter a valid password"
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
