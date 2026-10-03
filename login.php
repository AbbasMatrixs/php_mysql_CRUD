<?php

require_once "config/database.php";
require_once "config/config.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


/* If already logged in */
if (isset($_SESSION["user_id"])) {

    if ($_SESSION["role"] === "admin") {

        header("Location: " . BASE_URL . "/admin/dashboard.php");

    } else {

        header("Location: " . BASE_URL . "/student/dashboard.php");

    }

    exit();
}


$error = "";


/* Login Form */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";


    if ($email === "" || $password === "") {

        $error = "Please enter your email and password.";

    } else {

        $stmt = $pdo->prepare(
            "SELECT *
             FROM users
             WHERE email = ?"
        );

        $stmt->execute([$email]);

        $user = $stmt->fetch();


        if ($user && password_verify($password, $user["password"])) {

            session_regenerate_id(true);

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["name"] = $user["name"];
            $_SESSION["role"] = $user["role"];


            if ($user["role"] === "admin") {

                header(
                    "Location: " .
                    BASE_URL .
                    "/admin/dashboard.php"
                );

            } else {

                header(
                    "Location: " .
                    BASE_URL .
                    "/student/dashboard.php"
                );

            }

            exit();

        } else {

            $error = "Invalid email or password.";

        }
    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Login | Student Portal</title>


    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >


    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        body {

            font-family: "Inter", sans-serif;

            min-height: 100vh;

            background: #f4f7fb;

            color: #172033;

        }


        .login-container {

            min-height: 100vh;

            display: flex;

        }


        /* =========================
           LEFT SIDE
        ========================= */

        .login-left {

            width: 52%;

            background:
                linear-gradient(
                    145deg,
                    #172554,
                    #1e3a8a 50%,
                    #2563eb
                );

            position: relative;

            overflow: hidden;

            display: flex;

            align-items: center;

            padding: 70px;

            color: white;

        }


        .login-left::before {

            content: "";

            position: absolute;

            width: 420px;

            height: 420px;

            border-radius: 50%;

            background: rgba(255,255,255,0.07);

            top: -160px;

            right: -120px;

        }


        .login-left::after {

            content: "";

            position: absolute;

            width: 300px;

            height: 300px;

            border-radius: 50%;

            background: rgba(255,255,255,0.06);

            bottom: -130px;

            left: -100px;

        }


        .brand-content {

            position: relative;

            z-index: 2;

            max-width: 560px;

        }


        .brand-logo {

            display: flex;

            align-items: center;

            gap: 14px;

            margin-bottom: 80px;

        }


        .logo-box {

            width: 52px;

            height: 52px;

            background: rgba(255,255,255,0.14);

            border: 1px solid rgba(255,255,255,0.25);

            border-radius: 14px;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 19px;

            font-weight: 800;

            letter-spacing: -1px;

        }


        .brand-logo h2 {

            font-size: 20px;

            font-weight: 700;

        }


        .brand-logo span {

            display: block;

            margin-top: 3px;

            font-size: 11px;

            color: rgba(255,255,255,0.65);

            letter-spacing: 1px;

            text-transform: uppercase;

        }


        .brand-content h1 {

            font-size: 48px;

            line-height: 1.12;

            letter-spacing: -1.5px;

            margin-bottom: 22px;

        }


        .brand-content > p {

            font-size: 16px;

            line-height: 1.8;

            color: rgba(255,255,255,0.75);

            max-width: 470px;

        }


        .feature-list {

            margin-top: 42px;

            display: flex;

            flex-direction: column;

            gap: 17px;

        }


        .feature {

            display: flex;

            align-items: center;

            gap: 13px;

            font-size: 14px;

            color: rgba(255,255,255,0.86);

        }


        .feature-icon {

            width: 28px;

            height: 28px;

            border-radius: 50%;

            background: rgba(255,255,255,0.12);

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 12px;

        }


        /* =========================
           RIGHT SIDE
        ========================= */

        .login-right {

            width: 48%;

            display: flex;

            align-items: center;

            justify-content: center;

            padding: 40px;

            background: #f8fafc;

        }


        .login-box {

            width: 100%;

            max-width: 440px;

        }


        .mobile-logo {

            display: none;

        }


        .login-heading {

            margin-bottom: 34px;

        }


        .login-heading h1 {

            font-size: 30px;

            font-weight: 800;

            letter-spacing: -0.8px;

            margin-bottom: 9px;

            color: #111827;

        }


        .login-heading p {

            font-size: 14px;

            color: #6b7280;

            line-height: 1.6;

        }


        /* ERROR */

        .error-message {

            background: #fff1f2;

            border: 1px solid #fecdd3;

            color: #be123c;

            padding: 13px 15px;

            border-radius: 10px;

            font-size: 13px;

            margin-bottom: 22px;

        }


        /* FORM */

        .form-group {

            margin-bottom: 22px;

        }


        .form-label {

            display: block;

            font-size: 13px;

            font-weight: 600;

            color: #374151;

            margin-bottom: 9px;

        }


        .input-wrapper {

            position: relative;

        }


        .input-icon {

            position: absolute;

            left: 15px;

            top: 50%;

            transform: translateY(-50%);

            color: #9ca3af;

            font-size: 15px;

        }


        .form-input {

            width: 100%;

            height: 50px;

            border: 1px solid #dbe1ea;

            border-radius: 10px;

            background: white;

            padding: 0 45px;

            font-family: inherit;

            font-size: 14px;

            color: #111827;

            outline: none;

            transition: 0.2s;

        }


        .form-input:focus {

            border-color: #2563eb;

            box-shadow:
                0 0 0 3px rgba(37,99,235,0.10);

        }


        .password-toggle {

            position: absolute;

            right: 14px;

            top: 50%;

            transform: translateY(-50%);

            border: none;

            background: none;

            color: #9ca3af;

            cursor: pointer;

            font-size: 13px;

        }


        .password-toggle:hover {

            color: #2563eb;

        }


        /* LOGIN BUTTON */

        .login-button {

            width: 100%;

            height: 50px;

            border: none;

            border-radius: 10px;

            background: #2563eb;

            color: white;

            font-family: inherit;

            font-size: 14px;

            font-weight: 700;

            cursor: pointer;

            transition: 0.2s;

            margin-top: 5px;

        }


        .login-button:hover {

            background: #1d4ed8;

            transform: translateY(-1px);

            box-shadow:
                0 7px 18px rgba(37,99,235,0.20);

        }


        .login-button:active {

            transform: translateY(0);

        }


        .login-footer {

            text-align: center;

            margin-top: 32px;

            padding-top: 25px;

            border-top: 1px solid #e5e7eb;

        }


        .login-footer p {

            font-size: 12px;

            color: #9ca3af;

        }


        .login-footer strong {

            color: #6b7280;

        }


        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 900px) {

            .login-left {

                width: 45%;

                padding: 40px;

            }


            .login-right {

                width: 55%;

            }


            .brand-content h1 {

                font-size: 38px;

            }

        }


        @media (max-width: 700px) {

            .login-container {

                display: block;

            }


            .login-left {

                display: none;

            }


            .login-right {

                width: 100%;

                min-height: 100vh;

                padding: 25px;

            }


            .mobile-logo {

                display: flex;

                align-items: center;

                justify-content: center;

                gap: 10px;

                margin-bottom: 55px;

            }


            .mobile-logo .logo-box {

                background: #2563eb;

                color: white;

                border: none;

            }


            .mobile-logo h2 {

                font-size: 18px;

            }


            .login-heading h1 {

                font-size: 27px;

            }

        }

    </style>

</head>


<body>


<div class="login-container">


    <!-- LEFT SECTION -->

    <section class="login-left">

        <div class="brand-content">


            <div class="brand-logo">

                <div class="logo-box">
                    SP
                </div>

                <div>

                    <h2>
                        Student Portal
                    </h2>

                    <span>
                        Management System
                    </span>

                </div>

            </div>


            <h1>
                Manage your<br>
                academic journey.
            </h1>


            <p>
                A simple and secure platform for managing
                student information, academic records and
                account details in one place.
            </p>


            <div class="feature-list">


                <div class="feature">

                    <div class="feature-icon">
                        ✓
                    </div>

                    <span>
                        Easy student information management
                    </span>

                </div>


                <div class="feature">

                    <div class="feature-icon">
                        ✓
                    </div>

                    <span>
                        Secure account-based access
                    </span>

                </div>


                <div class="feature">

                    <div class="feature-icon">
                        ✓
                    </div>

                    <span>
                        Dedicated admin and student dashboards
                    </span>

                </div>


            </div>

        </div>

    </section>


    <!-- RIGHT SECTION -->

    <section class="login-right">


        <div class="login-box">


            <!-- Mobile Logo -->

            <div class="mobile-logo">

                <div class="logo-box">
                    SP
                </div>

                <h2>
                    Student Portal
                </h2>

            </div>


            <div class="login-heading">

                <h1>
                    Welcome back
                </h1>

                <p>
                    Sign in to access your Student Portal account.
                </p>

            </div>


            <?php if ($error !== ""): ?>

                <div class="error-message">

                    <?php echo htmlspecialchars($error); ?>

                </div>

            <?php endif; ?>


            <form method="POST">


                <div class="form-group">

                    <label class="form-label">
                        Email Address
                    </label>


                    <div class="input-wrapper">

                        <span class="input-icon">
                            ✉
                        </span>


                        <input
                            type="email"
                            name="email"
                            class="form-input"
                            placeholder="Enter your email"
                            value="<?php echo htmlspecialchars($_POST["email"] ?? ""); ?>"
                            autocomplete="email"
                            required
                        >

                    </div>

                </div>


                <div class="form-group">

                    <label class="form-label">
                        Password
                    </label>


                    <div class="input-wrapper">

                        <span class="input-icon">
                            🔒
                        </span>


                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="form-input"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            required
                        >


                        <button
                            type="button"
                            class="password-toggle"
                            id="togglePassword"
                        >
                            Show
                        </button>

                    </div>

                </div>


                <button
                    type="submit"
                    class="login-button"
                >
                    Sign In
                </button>


            </form>


            <div class="login-footer">

                <p>
                    <strong>Student Portal</strong>
                    &nbsp;•&nbsp;
                    PHP & MySQL Management System
                </p>

            </div>


        </div>


    </section>


</div>


<script>

    const passwordInput =
        document.getElementById("password");

    const togglePassword =
        document.getElementById("togglePassword");


    togglePassword.addEventListener(
        "click",
        function () {

            if (passwordInput.type === "password") {

                passwordInput.type = "text";

                togglePassword.textContent = "Hide";

            } else {

                passwordInput.type = "password";

                togglePassword.textContent = "Show";

            }

        }
    );

</script>


</body>

</html>