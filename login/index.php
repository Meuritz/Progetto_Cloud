<?php
session_start();

// Database configuration
$host = 'db-mysql';
$dbname = 'test';
$username_db = 'root';
$password_db = rtrim(file_get_contents("/run/secrets/db_password"));

try {

    $pdo = new PDO("mysql:host=$host;
        dbname=$dbname"
        , $username_db, 
        $password_db);
    
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

catch(PDOException $e) {
    
    die("Connection failed: " . $e->getMessage());

}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Authentication
    try {
        
        // prepare the query
        $stmt = $pdo->prepare("SELECT id, name, password FROM Users WHERE name = ?");
        
        // pass the inserted username to the query 
        $stmt->execute([$username]);
        
        // store the query result in a variable
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // check if the user inserted the correct password for the user
        if ($user && $password == $user['password']) {
            
            // change session status
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['id'];
            
            // redirect 
            header("Location: http://localhost:8080/webgl/");
            exit;

        } else {
           $error = 'Invalid username or password';
        }

    } catch(PDOException $e) {
        $error = 'Database error occurred';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        .login-container {
            max-width: 400px;
            margin: auto;
            margin-top: 10vh;
        }
        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="card">
                <div class="card-body p-4">
                    <h2 class="card-title text-center mb-4">Login</h2>
                    
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username:</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password:</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
