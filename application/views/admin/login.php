<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Pizza One</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #e74c3c;
            --dark: #2c3e50;
            --light: #f4f7f6;
            --white: #ffffff;
            --gray: #95a5a6;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--dark) 0%, #1a252f 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background: var(--white);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--primary);
        }

        .logo {
            font-size: 2.5rem;
            color: var(--primary);
            font-weight: 700;
            margin-bottom: 30px;
        }

        .logo span {
            color: var(--dark);
        }

        h2 {
            color: var(--dark);
            margin-bottom: 20px;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
            text-align: left;
        }

        .form-group i {
            position: absolute;
            left: 15px;
            top: 45px;
            color: var(--gray);
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: var(--dark);
            font-size: 0.9rem;
        }

        input {
            width: 100%;
            padding: 12px 12px 12px 45px;
            border: 1px solid #ddd;
            border-radius: 10px;
            outline: none;
            transition: all 0.3s;
        }

        input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 8px rgba(231, 76, 60, 0.2);
        }

        .btn-login {
            background: var(--primary);
            color: var(--white);
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .btn-login:hover {
            background: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
        }

        .error-msg {
            background: #fde8e8;
            color: #e74c3c;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.85rem;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="logo">Pizza<span>One</span></div>
        <h2>Admin Portal</h2>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="error-msg">
                <i class="fas fa-exclamation-circle"></i> <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>

        <?php echo form_open('admin/login'); ?>
        <div class="form-group">
            <label>Username</label>
            <i class="fas fa-user"></i>
            <input type="text" name="username" placeholder="Enter your username" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <i class="fas fa-lock"></i>
            <input type="password" name="password" placeholder="Enter your password" required>
        </div>
        <button type="submit" class="btn-login">LOGIN</button>
        <?php echo form_close(); ?>
    </div>
</body>

</html>