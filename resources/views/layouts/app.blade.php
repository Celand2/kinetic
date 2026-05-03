<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KINETIC - Luxury Investment Platform')</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #859de0 0%, #4f6099 100%);
            color: #1b0808;
            line-height: 1.6;
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Navbar */
        nav {
            background: rgba(20, 26, 46, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(201, 162, 39, 0.1);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        nav a {
            color: #ffffff;
            text-decoration: none;
            margin: 0 1.5rem;
            transition: color 0.3s ease;
        }

        nav a:hover {
            color: #c9a227;
        }

        nav .brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: #c9a227;
            margin-right: 2rem;
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-links {
            display: flex;
            align-items: center;
        }

        /* Main Content */
        main {
            min-height: calc(100vh - 60px);
            padding: 2rem 0;
        }

        /* Cards */
        .card {
            background: rgba(20, 26, 46, 0.5);
            border: 1px solid rgba(201, 162, 39, 0.1);
            border-radius: 15px;
            padding: 2rem;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .card:hover {
            border-color: rgba(201, 162, 39, 0.3);
            box-shadow: 0 8px 32px rgba(201, 162, 39, 0.1);
            transform: translateY(-2px);
        }

        .card-header {
            font-size: 1.5rem;
            font-weight: bold;
            color: #c9a227;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid rgba(201, 162, 39, 0.2);
            padding-bottom: 1rem;
        }

        /* Buttons */
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, #c9a227 0%, #d4b750 100%);
            color: #0b0f1a;
            text-decoration: none;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn:hover {
            box-shadow: 0 8px 20px rgba(201, 162, 39, 0.3);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: rgba(201, 162, 39, 0.2);
            color: #c9a227;
            border: 1px solid #c9a227;
        }

        .btn-secondary:hover {
            background: rgba(201, 162, 39, 0.3);
        }

        /* Forms */
        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #c9a227;
            font-weight: 600;
        }

        input, textarea, select {
            width: 100%;
            padding: 0.75rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(201, 162, 39, 0.2);
            border-radius: 8px;
            color: #ffffff;
            font-size: 1rem;
        }

        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: #c9a227;
            box-shadow: 0 0 15px rgba(201, 162, 39, 0.2);
        }

        /* Alert Messages */
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: rgba(76, 175, 80, 0.1);
            border-left: 3px solid #4caf50;
            color: #81c784;
        }

        .alert-error {
            background: rgba(244, 67, 54, 0.1);
            border-left: 3px solid #f44336;
            color: #ef5350;
        }

        /* Grid */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        th {
            background: rgba(201, 162, 39, 0.1);
            padding: 1rem;
            text-align: left;
            color: #c9a227;
            font-weight: 600;
            border-bottom: 1px solid rgba(201, 162, 39, 0.2);
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid rgba(201, 162, 39, 0.1);
        }

        tr:hover {
            background: rgba(201, 162, 39, 0.05);
        }

        /* Stats */
        .stat-box {
            background: rgba(201, 162, 39, 0.1);
            border: 1px solid rgba(201, 162, 39, 0.2);
            border-radius: 8px;
            padding: 1.5rem;
            text-align: center;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: #c9a227;
            margin: 0.5rem 0;
        }

        .stat-label {
            font-size: 0.9rem;
            color: #b0bfd9;
        }

        /* Footer */
        footer {
            background: rgba(20, 26, 46, 0.8);
            border-top: 1px solid rgba(201, 162, 39, 0.1);
            padding: 2rem 0;
            text-align: center;
            color: #b0bfd9;
            margin-top: 3rem;
        }

        @media (max-width: 768px) {
            .nav-links {
                flex-direction: column;
            }

            nav a {
                margin: 0.5rem 0;
            }

            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    {{-- <nav>
        <div class="container">
            <div class="nav-container">
                <div class="brand">KINETIC</div>
                <div class="nav-links">
                    @auth
                        <a href="{{ route('dashboard') }}">Dashboard</a>
                        <a href="{{ route('investments.index') }}">Investments</a>
                        <a href="{{ route('transactions.index') }}">Transactions</a>
                        <a href="{{ route('referral.dashboard') }}">Referrals</a>
                        <a href="{{ route('messages.inbox') }}">Messages</a>
                        @if(auth()->user()->role === 'super_admin')
                            <a href="{{ route('admin.users.index') }}">Admin</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" style="background: none; border: none; color: #ffffff; cursor: pointer; margin: 0 1.5rem;">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav> --}}

    <main>
        <div class="container">
            @if($message = session('success'))
                <div class="alert alert-success">{{ $message }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2026 KINETIC - Luxury Investment Platform. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
