<?php
session_start();
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
if ( isset($_POST['delete']) && isset($_POST['auto_id']) ) {
    $sql = "DELETE FROM autos WHERE auto_id = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_POST['auto_id']));
    $_SESSION['success'] = 'Record deleted';
    header( 'Location: view.php' ) ;
    return;
}

if ( ! isset($_GET['auto_id']) ) {
  $_SESSION['error'] = "Missing auto_id";
  header('Location: view.php');
  return;
}

$stmt = $pdo->prepare("SELECT make, auto_id FROM autos where auto_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['auto_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for user_id';
    header( 'Location: view.php' ) ;
    return;
}

?>
<p>Confirm: Deleting <?= htmlentities($row['make']) ?></p>

<form method="post">
<input type="hidden" name="auto_id" value="<?= $row['auto_id'] ?>">
<input type="submit" value="Delete" name="delete">
<a href="index.php">Cancel</a>
</form>