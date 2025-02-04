<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php');
check_login();

// Vérifier si une table a été sélectionnée
if (!isset($_SESSION['selected_table_id'])) {
    $_SESSION['error'] = "Veuillez d'abord sélectionner une table.";
    header("Location: tables.php");
    exit;
}

// Vérifier si les paramètres GET sont présents
if (!isset($_GET['prod_id']) || !isset($_GET['prod_name']) || !isset($_GET['prod_price'])) {
    $_SESSION['error'] = "Veuillez sélectionner un produit avant de passer une commande.";
    header("Location: orders.php");
    exit;
}

// Récupérer l'ID de la table sélectionnée
$table_id = $_SESSION['selected_table_id'];

// Récupérer les informations du produit
$prod_id = $_GET['prod_id'];
$prod_name = urldecode($_GET['prod_name']);
$prod_price = urldecode($_GET['prod_price']);

if (isset($_POST['make'])) {
    // Prévenir l'envoi de valeurs vides
    if (empty($_POST["order_code"]) || empty($_POST["customer_name"])) {
        $err = "Tous les champs sont obligatoires.";
    } else {
        $order_id = $_POST['order_id'];
        $order_code = $_POST['order_code'];
        $customer_id = $_POST['customer_id'];
        $customer_name = $_POST['customer_name'];
        $prod_qty = $_POST['prod_qty'];

        // Insérer les informations capturées dans la table `rpos_orders`
        $postQuery = "INSERT INTO rpos_orders (prod_qty, order_id, order_code, customer_id, customer_name, prod_id, prod_name, prod_price, table_id) 
                      VALUES(?,?,?,?,?,?,?,?,?)";
        $postStmt = $mysqli->prepare($postQuery);
        $rc = $postStmt->bind_param(
            'ssssssssi',
            $prod_qty,
            $order_id,
            $order_code,
            $customer_id,
            $customer_name,
            $prod_id,
            $prod_name,
            $prod_price,
            $table_id
        );
        $postStmt->execute();

        if ($postStmt) {
            // Mettre à jour le statut de la table en "occupée"
            $updateTableStatus = "UPDATE rpos_tables SET status = 1 WHERE table_id = ?";
            $updateStmt = $mysqli->prepare($updateTableStatus);
            $updateStmt->bind_param('i', $table_id);
            $updateStmt->execute();

            if ($updateStmt) {
                $success = "Commande passée avec succès." && header("refresh:1; url=payments.php");
            } else {
                $err = "Impossible de mettre à jour le statut de la table.";
            }
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
          <h2 class="text-white">Passer une commande</h2>
        </div>
      </div>
    </div>

    <!-- Page content -->
    <div class="container-fluid mt--8">
      <div class="row">
        <div class="col">
          <div class="card shadow">
            <div class="card-header border-0">
              <h3>Veuillez remplir tous les champs.</h3>
            </div>
            <div class="card-body">
              <!-- Affichage des messages d'erreur/succès -->
              <?php if (isset($err)): ?>
                  <div class="alert alert-danger" role="alert">
                      <?php echo htmlspecialchars($err); ?>
                  </div>
              <?php endif; ?>

              <?php if (isset($success)): ?>
                  <div class="alert alert-success" role="alert">
                      <?php echo htmlspecialchars($success); ?>
                  </div>
              <?php endif; ?>

              <form method="POST" enctype="multipart/form-data">
                <div class="form-row">
                  <div class="col-md-4">
                    <label>Nom du client</label>
                    <select class="form-control" name="customer_name" id="custName" onChange="getCustomer(this.value)">
                      <option value="">Sélectionnez un nom de client</option>
                      <?php
                      // Charger tous les clients
                      $ret = "SELECT * FROM rpos_customers";
                      $stmt = $mysqli->prepare($ret);
                      $stmt->execute();
                      $res = $stmt->get_result();
                      while ($cust = $res->fetch_object()) {
                      ?>
                        <option><?php echo $cust->customer_name; ?></option>
                      <?php } ?>
                    </select>
                    <input type="hidden" name="order_id" value="<?php echo $orderid; ?>" class="form-control">
                  </div>
                  <div class="col-md-4">
                    <label>ID du client</label>
                    <input type="text" name="customer_id" readonly id="customerID" class="form-control">
                  </div>
                  <div class="col-md-4">
                    <label>Code de commande</label>
                    <input type="text" name="order_code" value="<?php echo $alpha; ?>-<?php echo $beta; ?>" class="form-control" readonly>
                  </div>
                </div>
                <hr>
                <div class="form-row">
                  <div class="col-md-6">
                    <label>Prix du produit ($)</label>
                    <input type="text" readonly name="prod_price" value="$ <?php echo htmlspecialchars($prod_price); ?>" class="form-control">
                  </div>
                  <div class="col-md-6">
                    <label>Quantité de produit</label>
                    <input type="number" name="prod_qty" class="form-control" min="1" required>
                  </div>
                </div>
                <hr>
                <!-- Champ caché pour l'ID de la table -->
                <input type="hidden" name="table_id" value="<?php echo $table_id; ?>" />
                <br>
                <div class="form-row">
                  <div class="col-md-6">
                    <input type="submit" name="make" value="Passer la commande" class="btn btn-success">
                  </div>
                </div>
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