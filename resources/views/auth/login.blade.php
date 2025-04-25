<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - HR Management System</title>

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #343a40;
        }

        .form-control {
            margin-bottom: 15px;
        }

        .btn-login {
            width: 100%;
            padding: 10px;
            background-color: #0d6efd;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 4px;
        }

        .btn-login:hover {
            background-color: #0b5ed7;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Field -->
            <div>
                <input type="email" name="email" class="form-control" placeholder="Email" required autofocus>
            </div>

            <!-- Password Field -->
            <div>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>

            <!-- Remember Me Checkbox -->
            <div class="form-check mb-3">
                <input type="checkbox" name="remember" id="remember" class="form-check-input">
                <label for="remember" class="form-check-label">Remember Me</label>
            </div>

            <!-- Login Button -->
            <button type="submit" class="btn btn-login">Login</button>

            <!-- Error Message -->
            @if ($errors->any())
                <div class="alert alert-danger mt-3">
                    <ul>
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
</body>
</html>