<?php
try 
{
    $pdo = new PDO("mysql:host=localhost;dbname=misc", "fred", "zap");
    // set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
    echo "Connection failed: " . $e->getMessage();
    die();
}
if ( isset($_POST['make']) && isset($_POST['model'])
     && isset($_POST['year']) && isset($_POST['mileage'])  && isset($_POST['auto_id']) ) {

    // Data validation
    if ( strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1) {
        $_SESSION['error'] = 'Missing data';
        header("Location: edit.php?auto_id=".$_POST['auto_id']);
        return;
    }

   
    $sql = "UPDATE autos SET make = :make,
            model = :model, year = :year, mileage = :mileage
            WHERE auto_id = :auto_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':make' => $_POST['make'],
        ':model' => $_POST['model'],
        ':year' => $_POST['year'],
		':mileage' => $_POST['mileage'],
        ':auto_id' => $_POST['auto_id']));
    $_SESSION['success'] = 'Record added';
    header( 'Location: view.php' ) ;
    return;
}
if ( ! isset($_GET['auto_id']) ) {
  $_SESSION['error'] = "Missing user_id";
  header('Location: view.php');
  return;
}




// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
$stmt = $pdo->prepare("SELECT * FROM autos where auto_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['auto_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$n = htmlentities($row['make']);
$e = htmlentities($row['Model']);
$p = htmlentities($row['year']);
$q = htmlentities($row['mileage']);
$auto_id = $row['auto_id'];
?>
<p>Edit User</p>
<?php
          if ( isset($_SESSION['error']) ) {
           echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
           unset($_SESSION['error']);
           }
           if ( isset($_SESSION['success']) ) {
           echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
          unset($_SESSION['success']);
          }
         ?>
   <form method="post" class="form-horizontal">
                <div class="form-group">
                    <label class="control-label col-sm-2" for="make">Make:</label>
                    <div class="col-sm-3">
                        <input class="form-control" type="text" name="make" id="make" value="<?= $n ?>">
                    </div>
                </div>
				 <div class="form-group">
                    <label class="control-label col-sm-2" for="year">Model:</label>
                    <div class="col-sm-3">
                        <input class="form-control" type="text" name="model" id="model" value="<?= $e ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="year">Year:</label>
                    <div class="col-sm-3">
                        <input class="form-control" type="text" name="year" id="year" value="<?= $p ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="mileage">Mileage:</label>
                    <div class="col-sm-3">
                        <input class="form-control" type="text" name="mileage" id="mileage" value="<?= $q ?>">
                    </div>
                </div>


<input type="hidden" name="auto_id" value="<?= $auto_id ?>">
<p><input type="submit" value="Save"/>
<a href="view.php">Cancel</a></p>
</form>
