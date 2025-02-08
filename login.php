

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Project Tracker Pro</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background: url('./643353.png') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #fff;
            overflow: hidden;
            transition: background 0.3s ease-in-out;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
        }

        .login-container {
            position: relative;
            background-color: rgba(0, 0, 0, 0.7);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
            width: 100%;
            max-width: 400px;
            text-align: center;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(30px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .login-container h1 {
            margin-bottom: 1.5rem;
            font-size: 2rem;
            color: #fff;
            letter-spacing: 1px;
        }

        .login-container p {
            margin-bottom: 2rem;
            font-size: 1rem;
            color: #ccc;
        }

        .form-group {
            text-align: left;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 1rem;
            border: 2px solid #555;
            border-radius: 6px;
            font-size: 1rem;
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            margin-bottom: 1rem;
            transition: all 0.3s;
            box-sizing: border-box; 
        }

        .form-group input:focus {
            outline: none;
            border-color: #2575fc;
            background: rgba(255, 255, 255, 0.3);
            box-shadow: 0 0 8px rgba(37, 117, 252, 0.5);
        }

        .form-group input::placeholder {
            color: #ddd;
        }

        .role-select {
            margin: 1rem 0;
        }

        .role-select select {
            width: 100%;
            padding: 1rem;
            border: 2px solid #555;
            border-radius: 6px;
            font-size: 1rem;
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            transition: all 0.3s;
            box-sizing: border-box; 
        }

        .role-select select:focus {
            outline: none;
            border-color: #2575fc;
            background: rgba(255, 255, 255, 0.3);
        }

        .login-btn {
            background: #FF5722; 
            color: #fff;
            border: none;
            padding: 1rem;
            border-radius: 6px;
            font-size: 1.2rem;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s ease;
        }

        .login-btn:hover {
            background: #e64a19; 
            transform: scale(1.05);
        }

        .form-group .icon {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
            color: #888;
        }

        .form-group input {
            padding-left: 2.5rem; 
        }

    </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="login-container">
        <h1>Welcome to Project <span style="color: #FF5722;">Tracker</span> Pro</h1>
        <p>Login to manage your projects efficiently</p>
        <form action="loginHelper.php" method="POST" id="loginForm">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required>
                <i class="icon fas fa-user"></i>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
                <i class="icon fas fa-lock"></i>
            </div>
            <div class="role-select">
                <label for="role">Select Role</label>
                <select id="role" name="role" required>
                    <option value="">-- Select Role --</option>
                    <option value="admin">Admin</option>
                    <option value="manager">Manager</option>
                    <option value="member">Member</option>
                </select>
            </div>
            <button type="submit" name = "submit" class="login-btn">Login</button>
        </form>
    </div>

    
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script>
        // js for form validation 
        const form = document.getElementById('loginForm');
        form.addEventListener('submit', function(event) {
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            if (!username || !password) {
                alert('Please fill in all fields!');
                event.preventDefault();
            }
        });
    </script>
</body>
</html>
