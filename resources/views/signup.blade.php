<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>StockMarket - Sign Up</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: #f3f4f6;
            font-family: 'Segoe UI', sans-serif;
        }

       .auth-wrapper {
    padding-top: 20px;
    padding-bottom: 20px;
}
.form-control:focus,
.input-group-text:focus {
    box-shadow: none !important;
    border-color: #2563eb;
}
        .auth-card {
            max-width: 450px;
            width: 100%;
            border-radius: 20px;
            padding: 40px;
            background: #ffffff;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        }

        .brand-title {
            font-weight: 700;
        }

        .brand-title span {
            color: #6c757d;
        }

        .form-control {
            height: 50px;
            border-radius: 12px;
        }

        .input-group-text {
            border-radius: 12px 0 0 12px;
            background: #f1f5f9;
            border: 1px solid #dee2e6;
        }

        .input-group .form-control {
            border-radius: 0 12px 12px 0;
        }

        .btn-primary-custom {
            background: linear-gradient(90deg, #2563eb, #3b82f6);
            border: none;
            height: 50px;
            border-radius: 12px;
            font-weight: 600;
        }

        .btn-primary-custom:hover {
            opacity: 0.9;
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 25px 0;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #dee2e6;
        }

        .divider:not(:empty)::before {
            margin-right: .75em;
        }

        .divider:not(:empty)::after {
            margin-left: .75em;
        }

        .social-btn {
            border-radius: 12px;
            height: 45px;
            border: 1px solid #dee2e6;
            background: #fff;
            font-weight: 500;
        }

        .social-btn:hover {
            background: #f8f9fa;
        }
    </style>
</head>
<body>
<div class="container py-5">
<div class="d-flex align-items-center justify-content-center auth-wrapper">
    <div class="auth-card">

        <!-- Logo / Title -->
        <h2 class="text-center mb-2 brand-title">
            Nifty<span>News</span>
        </h2>
        <p class="text-center text-muted mb-4">
            Create your account to get started.
        </p>

        <!-- Form -->
        <form method="POST" action="{{url('/register')}}">
            @csrf

            <!-- Full Name -->
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fa-regular fa-user"></i>
                    </span>
                    <input type="text" name="name" class="form-control"
                           placeholder="Enter your name" value="{{ old('name') }}">
                </div>
                @error('name')
    <small class="text-danger">{{ $message }}</small>
@enderror
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fa-regular fa-envelope"></i>
                    </span>
                    <input type="text" name="email" class="form-control"
                           placeholder="Enter your email" value="{{ old('email') }}">
                </div>
                @error('email')
    <small class="text-danger">{{ $message }}</small>
@enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input type="password" name="password" id="password"
                           class="form-control"
                           placeholder="Enter password"  value="{{ old('password') }}">
                    <span class="input-group-text" onclick="togglePassword('password')" style="cursor:pointer;">
                        <i class="fa-regular fa-eye"></i>
                    </span>
                </div>
                  @error('password')
    <small class="text-danger">{{ $message }}</small>
@enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input type="password" name="password_confirmation"
                           id="confirmPassword"
                           class="form-control"
                           placeholder="Confirm password" value="{{ old('password_confirmation') }}">
                    <span class="input-group-text" onclick="togglePassword('confirmPassword')" style="cursor:pointer;">
                        <i class="fa-regular fa-eye"></i>
                    </span>
                </div> 
            </div>

            <!-- Role Selection -->
<div class="mb-3">
    <label class="form-label">Select Role</label>
    <div class="input-group">
        <span class="input-group-text">
            <i class="fa-solid fa-user-tag"></i>
        </span>
        <select name="role" class="form-control">
            <option value="">Choose Role</option>
            <option value="editor" {{ old('role') == 'editor' ? 'selected' : '' }}>Editor</option>
            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
        </select>
    </div>
    @error('role')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>
            <!-- Terms -->
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" name="terms" {{ old('terms') ? 'checked' : '' }}>
                <label class="form-check-label">
                    I agree to the
                    <a href="#" class="text-decoration-none">Terms of Service</a>
                    and
                    <a href="#" class="text-decoration-none">Privacy Policy</a>
                </label>
                 @error('terms')
        <small class="text-danger">{{ $message }}</small>
    @enderror
            </div>

            <!-- Submit -->
            <button type="submit" class="btn btn-primary-custom w-100">
                Create Account <i class="fa-solid fa-arrow-right ms-2"></i>
            </button>
        </form>

        <!-- Divider  
        <div class="divider">Or sign up with</div>

   
        <div class="row g-2">
            <div class="col-6">
                <button class="btn social-btn w-100">
                    <i class="fa-brands fa-google me-2"></i> Google
                </button>
            </div>
            <div class="col-6">
                <button class="btn social-btn w-100">
                    <i class="fa-brands fa-github me-2"></i> GitHub
                </button>
            </div>
        </div> -->

        <!-- Login Link -->
        <p class="text-center mt-4 mb-0">
            Already have an account?
            <a href="{{ url('/signin') }}" class="fw-semibold text-decoration-none">
                Sign in
            </a>
        </p>

    </div>
</div>
</div>
<script>
    function togglePassword(id) {
        const input = document.getElementById(id);
        input.type = input.type === "password" ? "text" : "password";
    }
</script>

</body>
</html>
