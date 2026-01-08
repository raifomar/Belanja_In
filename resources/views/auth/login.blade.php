<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Kasir - BelanjaIn</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #3d3d3d;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-card {
            background: white;
            padding: 50px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }

        .login-card img {
            height: 130px;
            margin-bottom: 20px;
        }

        .login-card h2 {
            color: #1E293B;
            font-weight: 800;
            font-size: 1.8rem;
            margin-bottom: 35px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .form-group {
            text-align: left;
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #475569;
            margin-bottom: 10px;
            font-size: 1rem;
        }

        .form-group input {
            width: 100%;
            padding: 15px;
            border: 2px solid #E2E8F0;
            border-radius: 10px;
            font-size: 1.1rem;
            box-sizing: border-box;
            outline: none;
            transition: 0.3s;
        }

        .form-group input:focus {
            border-color: #1F3A5F;
        }

        .btn-login {
            width: 100%;
            background: #1F3A5F;
            color: white;
            border: none;
            padding: 18px;
            border-radius: 10px;
            font-weight: 800;
            font-size: 1.2rem;
            cursor: pointer;
            margin-top: 15px;
            transition: 0.3s;
            text-transform: uppercase;
            box-shadow: 0 4px 15px rgba(243, 112, 33, 0.3);
        }

        .btn-login:hover {
            background: #2e5c9e;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

    <div class="login-card">
        <img src="{{ asset('images/logo BelanjaIn.png') }}" alt="Logo BelanjaIn">
        
        <h2>Login Kasir</h2>

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="admin@kasir.com" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="********" required>
            </div>

            <button type="submit" class="btn-login">Masuk</button>
        </form>
    </div>

</body>
</html>