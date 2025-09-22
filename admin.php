<?php
session_start();
$AUTH_PASS = 'changeMe123'; // CHANGE THIS

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}

if (!isset($_SESSION['authed'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['password'] ?? '') === $AUTH_PASS) {
        $_SESSION['authed'] = true;
        header('Location: admin.php');
        exit;
    } else {
        echo '<!doctype html><html><head><meta charset="utf-8"><title>Admin Login</title></head><body>';
        echo '<form method="POST" style="max-width:320px;margin:80px auto;font-family:Arial">';
        echo '<h3>Feedback Admin Login</h3>';
        echo '<input type="password" name="password" placeholder="Password" style="width:100%;padding:8px"><br><br>';
        echo '<button type="submit" style="padding:8px 12px">Login</button>';
        echo '</form></body></html>';
        exit;
    }
}

$db = new PDO('sqlite:' . __DIR__ . '/private_data/feedback.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$rows = $db->query("SELECT * FROM feedback ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Feedback Admin</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #ccc; padding: 8px; vertical-align: top; }
    th { background: #eee; }
    .bar { margin-bottom: 16px; }
  </style>
</head>
<body>
  <div class="bar">
    <a href="export.php">Export CSV</a> |
    <a href="admin.php?logout=1">Logout</a>
  </div>
  <h1>Feedback History</h1>
  <table>
    <tr>
      <th>ID</th><th>Date</th><th>Salesperson</th><th>Customer</th>
      <th>Email</th><th>Phone</th><th>Rating</th><th>Comments</th>
    </tr>
    <?php foreach ($rows as $r): ?>
      <tr>
        <td><?= htmlspecialchars($r['id']) ?></td>
        <td><?= htmlspecialchars($r['created_at']) ?></td>
        <td><?= htmlspecialchars($r['salesperson']) ?></td>
        <td><?= htmlspecialchars($r['customer_name']) ?></td>
        <td><?= htmlspecialchars($r['customer_email']) ?></td>
        <td><?= htmlspecialchars($r['customer_phone']) ?></td>
        <td><?= htmlspecialchars($r['rating']) ?></td>
        <td><?= nl2br(htmlspecialchars($r['comments'])) ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
</body>
</html>
