<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - WorkWise HR System</title>

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #4361ee;
            --primary-dark: #3a56d4;
            --secondary-color: #3f37c9;
            --accent-color: #4cc9f0;
            --light-gray: #f8f9fa;
            --dark-gray: #343a40;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-gray);
            background-image: linear-gradient(135deg, #f5f7fa 0%, #e4f1fe 100%);
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
        }

        .login-container {
            max-width: 420px;
            width: 100%;
            margin: 0 auto;
            padding: 40px 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            position: relative;
            overflow: hidden;
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo h1 {
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 5px;
            font-size: 28px;
        }

        .logo p {
            color: #6c757d;
            font-size: 14px;
            margin: 0;
        }

        .form-control {
            height: 48px;
            border-radius: 6px;
            border: 1px solid #e0e0e0;
            padding-left: 15px;
            margin-bottom: 20px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        }

        .input-group-text {
            background-color: white;
            border-right: none;
        }

        .form-control.with-icon {
            border-left: none;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: var(--primary-color);
            border: none;
            color: white;
            font-size: 16px;
            font-weight: 500;
            border-radius: 6px;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .btn-login:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
        }

        .form-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #6c757d;
        }

        .form-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .alert {
            border-radius: 6px;
            padding: 12px 15px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="logo">
                <h1>WorkWise</h1>
                <p>Human Resources Management System</p>
            </div>
            
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Field -->
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control with-icon" placeholder="Email address" required autofocus>
                </div>

                <!-- Password Field -->
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" name="password" class="form-control with-icon" placeholder="Password" required>
                </div>


                <!-- Login Button -->
                <button type="submit" class="btn btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i> Login
                </button>

                <!-- Error Message -->
                @if ($errors->any())
                    <div class="alert alert-danger mt-3">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Session Status -->
                @if (session('status'))
                    <div class="alert alert-success mt-3">
                        {{ session('status') }}
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>