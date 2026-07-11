<?php         
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}
?>
<!doctype html>
<html lang="id">
<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>SECURITY RANK</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="assets/sidebar.css">

</head>
<body>

<div class="sidebar" id="sidebar">

    <div class="sidebar-brand">
        <img src="assets/logo.png" class="sidebar-logo" alt="Logo">
        <h4 class="brand-title">SECURITY RANK</h4>
    </div>

   
    <a href="dashboard.php">
        <i class="bi bi-speedometer2 icon"></i>
        <span class="text">Dashboard</span>
    </a>

    <a href="kriteria.php">
        <i class="bi bi-list-check icon"></i>
        <span class="text">Kriteria</span>
    </a>

    <a href="alternatif.php">
        <i class="bi bi-people-fill icon"></i>
        <span class="text">Alternatif</span>
    </a>

    <a href="perhitungan.php">
        <i class="bi bi-calculator-fill icon"></i>
        <span class="text">Perhitungan SAW</span>
    </a>

    <a href="perankingan.php">
        <i class="bi bi-bar-chart-line-fill icon"></i>
        <span class="text">Perankingan</span>
    </a>

    <hr class="bg-light">

  
    <a href="logout.php" class="mt-auto text-danger" style="margin-top:auto;">
        <i class="bi bi-box-arrow-right icon"></i>
        <span class="text">Logout</span>
    </a>

</div>


<div class="topbar" id="topbar">

    <!-- Toggle Sidebar -->
    <button class="btn-navy me-3" id="toggleSidebar">
        <i class="bi bi-layout-sidebar-inset"></i>
    </button>

    <!-- Admin Greeting -->
    <div class="admin-greet d-flex align-items-center me-3">
        <i class="bi bi-person-badge-fill me-2" style="font-size:20px;"></i>
        <span style="font-size:16px;">Hi, <?php echo htmlentities($_SESSION['user']); ?></span>
    </div>

</div>

<script>

document.getElementById("toggleSidebar").onclick = function () {
    document.getElementById("sidebar").classList.toggle("collapsed");
    document.getElementById("topbar").classList.toggle("collapsed");
    var content = document.getElementById("content");
    if (content) content.classList.toggle("collapsed");
};
</script>

</body>
</html>
