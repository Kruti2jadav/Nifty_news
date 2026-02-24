<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title> NiftyNews - Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Icons --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: #f3f4f6;
            font-family: 'Segoe UI', sans-serif;
        }

        .login-card {
            max-width: 460px;
            margin: 60px auto;
            padding: 40px;
            border-radius: 20px;
            background: #ffffff;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        }

        .brand-title {
            font-weight: 700;
            font-size: 32px;
        }

        .brand-title span {
            color: #6c757d;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 15px 12px 45px;
            background: #eef2f7;
            border: 1px solid #dce3ec;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #3b82f6;
        }

        .input-icon {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .btn-primary {
            background: #2f5fd0;
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background: #244bb5;
        }

        .divider {
            text-align: center;
            margin: 25px 0;
            position: relative;
        }

        .divider::before,
        .divider::after {
            content: "";
            height: 1px;
            width: 30%;
            background: #dee2e6;
            position: absolute;
            top: 50%;
        }

        .divider::before {
            left: 0;
        }

        .divider::after {
            right: 0;
        }

        .divider span {
            background: #fff;
            padding: 0 10px;
            color: #6c757d;
        }

        .social-btn {
            border-radius: 12px;
            padding: 10px;
            border: 1px solid #dee2e6;
            background: #fff;
            font-weight: 500;
            transition: 0.3s;
        }

        .social-btn:hover {
            background: #f8f9fa;
        }

        .small-link {
            color: #2f5fd0;
            text-decoration: none;
            font-weight: 500;
        }

        .small-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-card">

    <div class="text-center mb-4">
        <div class="brand-title">
            Nifty<span>News</span>
        </div>
        <p class="text-muted mt-2">
            Welcome back! Please login to your account.
        </p>
    </div>

    <form method="POST" action="{{route('login')}}">
        @csrf

        {{-- Email --}}
        <div class="mb-3 position-relative">
            <i class="fa-regular fa-envelope input-icon"></i>
            <input type="email" 
                   name="email" 
                   class="form-control" 
                   placeholder="Email Address"
                   >
        </div>

        {{-- Password --}}
        <div class="mb-3 position-relative">
            <i class="fa-solid fa-lock input-icon"></i>
            <input type="password" 
                   name="password" 
                   class="form-control" 
                   placeholder="Password"
                   >
        </div>

        {{-- Remember & Forgot --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <input type="checkbox" name="remember" value="1">
                <label class="text-muted"> Remember me</label>
            </div>
           <!-- <a href="#" class="small-link">Forgot password?</a>-->
        </div>

        {{-- Sign In --}}
        <button type="submit" class="btn btn-primary w-100">
            Sign In <i class="fa-solid fa-arrow-right ms-2"></i>
        </button>

    </form>

    {{-- Divider --}}
    <div class="divider">  
        <span>Or continue with</span>
    </div>

    {{-- Social Buttons --}}
  <!--  <div class="d-flex gap-3">
        <button class="btn social-btn w-50">
            <i class="fa-brands fa-google me-2"></i> Google
        </button>
        <button class="btn social-btn w-50">
            <i class="fa-brands fa-github me-2"></i> GitHub
        </button>
    </div>-->

    {{-- Sign Up --}}
    <div class="text-center mt-4">
        <span class="text-muted">Don't have an account?</span>
        <a href="{{url('/signup')}}" class="small-link"> Sign up</a>
    </div>

</div>

</body>
</html>
