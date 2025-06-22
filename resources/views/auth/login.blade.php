<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - FastLaundry</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #F3F4F6;
            margin: 0;
        }

        .main-container {
            display: flex;
            min-height: 100vh;
        }

        .info-panel {
            flex: 1;
            background: #4F46E5;
            color: white;
            padding: 4rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .info-panel::before {
            content: "";
            position: absolute;
            top: -100px;
            left: -100px;
            width: 400px;
            height: 400px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .info-panel::after {
            content: "";
            position: absolute;
            bottom: -50px;
            right: -150px;
            width: 500px;
            height: 500px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .logo-icon {
            font-size: 3.5rem;
            font-weight: 700;
            line-height: 1;
            z-index: 10;
        }

        .info-text h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            z-index: 10;
            position: relative;
        }

        .info-text p {
            font-size: 1.125rem;
            max-width: 400px;
            color: rgba(255, 255, 255, 0.8);
            z-index: 10;
            position: relative;
        }

        .copyright {
            font-size: 0.875rem;
            color: rgba(255, 255, 255, 0.6);
            z-index: 10;
            position: relative;
        }

        .form-panel {
            flex: 1;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .form-container {
            width: 100%;
            max-width: 380px;
        }

        .form-logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1F2937;
            margin-bottom: 2.5rem;
        }

        .form-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.5rem;
        }

        .form-subtitle {
            color: #6B7280;
            margin-bottom: 2rem;
        }

        .form-subtitle a {
            color: #4F46E5;
            font-weight: 500;
            text-decoration: none;
        }

        .form-subtitle a:hover {
            text-decoration: underline;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #D1D5DB;
            border-radius: 8px;
            transition: all 0.2s ease;
            box-sizing: border-box;
        }

        .form-group input:focus {
            outline: none;
            border-color: #4F46E5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .btn {
            width: 100%;
            padding: 0.875rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-primary {
            background: #111827;
            color: white;
            border: 1px solid #111827;
        }

        .btn-primary:hover {
            background: #374151;
        }

        .btn-secondary {
            background: white;
            color: #374151;
            border: 1px solid #D1D5DB;
            margin-top: 1rem;
        }

        .btn-secondary:hover {
            background-color: #F9FAFB;
        }

        .forgot-password {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.875rem;
        }

        .forgot-password a {
            color: #4F46E5;
            font-weight: 500;
        }

        .error {
            color: #EF4444;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        @media (max-width: 768px) {
            .info-panel {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="main-container">
        <!-- Info Panel -->
        <div class="info-panel">
            <div>
                <div class="logo-icon">*</div>
                <div class="info-text">
                    <h1>Hello FastLaundry! 👋</h1>
                    <p>Streamline your laundry operations with our comprehensive management system.</p>
                </div>
            </div>
            <div class="copyright">
                © {{ date('Y') }} FastLaundry. All rights reserved.
            </div>
        </div>

        <!-- Form Panel -->
        <div class="form-panel">
            <div class="form-container">
                <div class="form-logo">FastLaundry</div>
                <h2 class="form-title">Welcome Back!</h2>
                <p class="form-subtitle">
                    Don't have an account?
                    <a href="{{ route('register') }}">Create a new account now.</a>
                </p>

                @if (session('status'))
                    <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            autocomplete="username" placeholder="hisalim.ux@gmail.com">
                        @error('email') <p class="error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            placeholder="••••••••">
                        @error('password') <p class="error">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Login Now</button>

                    <button type="button" class="btn btn-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 48 48">
                            <path fill="#FFC107"
                                d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12s5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24s8.955,20,20,20s20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z">
                            </path>
                            <path fill="#FF3D00"
                                d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z">
                            </path>
                            <path fill="#4CAF50"
                                d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.222,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z">
                            </path>
                            <path fill="#1976D2"
                                d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.574l6.19,5.238C42.022,35.244,44,30.036,44,24C44,22.659,43.862,21.35,43.611,20.083z">
                            </path>
                        </svg>
                        Login with Google
                    </button>
                </form>

                <div class="forgot-password">
                    Forgot password? <a href="{{ route('password.request') }}">Click here</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>