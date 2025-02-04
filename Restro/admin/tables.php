<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

if (isset($_GET['table_id'])) {
  $_SESSION['selected_table_id'] = $_GET['table_id'];
  header("Location: orders.php");
  exit;
}

// Récupérer les tables depuis la base de données
$tables = [];
$ret = "SELECT * FROM rpos_tables";
$stmt = $mysqli->prepare($ret);
$stmt->execute();
$res = $stmt->get_result();
while ($table = $res->fetch_object()) {
    $tables[] = [
        'id' => $table->table_id,
        'number' => $table->table_number,
        'status' => $table->status // 0 = libre, 1 = occupée
    ];
}

require_once('partials/_head.php');
?>

<body>
  <!-- Sidenav -->
  <?php require_once('partials/_sidebar.php'); ?>

  <!-- Main content -->
  <div class="main-content">
    <!-- Top navbar -->
    <?php require_once('partials/_topnav.php'); ?>

    <!-- Header -->
    <div style="background-image: url(assets/img/theme/restro00.jpg); background-size: cover;" class="header pb-8 pt-5 pt-md-8">
      <span class="mask bg-gradient-dark opacity-8"></span>
      <div class="container-fluid">
        <div class="header-body">
          <h2 class="text-white">Tables</h2>
          <a href="add_table.php" class="btn btn-primary mb-4">Ajouter une table</a>
        </div>
      </div>
    </div>

    <!-- Page content -->
    <div class="container-fluid mt--8">
      <div class="row">
        <?php foreach ($tables as $table): ?>
          <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card <?php echo ($table['status'] == 1) ? 'bg-danger text-white' : 'bg-light'; ?>" style="cursor: pointer;">
              <div class="card-body text-center">
                <h4><?php echo 'Table ' . $table['number']; ?></h4>
                <p><?php echo ($table['status'] == 1) ? 'Occupée' : 'Libre'; ?></p>
                <a href="select_table.php?table_id=<?php echo $table['id']; ?>" class="btn btn-sm <?php echo ($table['status'] == 1) ? 'btn-secondary disabled' : 'btn-primary'; ?>">
                  <?php echo ($table['status'] == 1) ? 'Réservée' : 'Sélectionner'; ?>
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Footer -->
      <?php require_once('partials/_footer.php'); ?>
    </div>
  </div>

  <!-- Argon Scripts -->
  <?php require_once('partials/_scripts.php'); ?>
</body>
</html>