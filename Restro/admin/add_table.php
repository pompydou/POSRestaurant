<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

// Ajout d'une nouvelle table
if (isset($_POST['add_table'])) {
    $table_number = $_POST['table_number'];
    $status = '0'; // Par défaut, la table est libre

    // Vérifier si le numéro de table existe déjà
    $checkQuery = "SELECT * FROM rpos_tables WHERE table_number = ?";
    $stmt = $mysqli->prepare($checkQuery);
    $stmt->bind_param('i', $table_number);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $err = "Le numéro de table existe déjà.";
    } else {
        // Insérer la nouvelle table dans la base de données
        $insertQuery = "INSERT INTO rpos_tables (table_number, status) VALUES (?, ?)";
        $stmt = $mysqli->prepare($insertQuery);
        $stmt->bind_param('is', $table_number, $status);
        $stmt->execute();

        if ($stmt) {
            $success = "Table ajoutée avec succès." && header("refresh:1; url=tables.php");
        } else {
            $err = "Veuillez réessayer ou essayer plus tard.";
        }
    }
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
          <h2 class="text-white">Ajouter une table</h2>
        </div>
      </div>
    </div>

    <!-- Page content -->
    <div class="container-fluid mt--8">
      <div class="row">
        <div class="col">
          <div class="card shadow">
            <div class="card-header border-0">
              <h3>Ajouter une nouvelle table</h3>
            </div>
            <div class="card-body">
              <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                  <label for="table_number">Numéro de la table</label>
                  <input type="number" name="table_number" id="table_number" class="form-control" min="1" required>
                </div>
                <button type="submit" name="add_table" class="btn btn-success">Ajouter la table</button>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <?php require_once('partials/_footer.php'); ?>
    </div>
  </div>

  <!-- Argon Scripts -->
  <?php require_once('partials/_scripts.php'); ?>
</body>
</html>