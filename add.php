<?php

session_start();

if ( ! isset($_SESSION['name']) ) {
	die('ACCESS DENIED');
}

// If the user requested logout go back to index.php
if ( isset($_POST['logout']) ) {
    header('Location: logout.php');
    return;
}

$status = false;

if ( isset($_SESSION['status']) ) {
	$status = $_SESSION['status'];
	$status_color = $_SESSION['color'];

	unset($_SESSION['status']);
	unset($_SESSION['color']);
}

try 
{
    $pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc', 'fred', 'zap');
    // See the "errors" folder for details...
   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
    echo "Connection failed: " . $e->getMessage();
    die();
}

$name = htmlentities($_SESSION['name']);

$_SESSION['color'] = 'red';

// Check to see if we have some POST data, if we do process it
if (isset($_POST['mileage']) && isset($_POST['model']) && isset($_POST['year']) && isset($_POST['make'])) 
{
   
    if (strlen($_POST['make']) < 1)
    {
        //$status = "Make is required";
        $_SESSION['status'] = "All values are required";
        header("Location: add.php");
		return;
    }
	else  if ( !is_numeric($_POST['mileage']) || !is_numeric($_POST['year']) ) 
    {
        //$status = "Mileage and year must be numeric";
        $_SESSION['status'] = "Mileage and year must be numeric";
        header("Location: add.php");
		return;
    } 
    else 
    {
        $make = htmlentities($_POST['make']);
		$model = htmlentities($_POST['model']);
        $year = htmlentities($_POST['year']);
        $mileage = htmlentities($_POST['mileage']);

        $stmt = $pdo->prepare("
            INSERT INTO autos (make, model, year, mileage) 
            VALUES (:make, :model, :year, :mileage)
        ");

        $stmt->execute([
            ':make' => $make, 
			':model' => $model,
            ':year' => $year,
            ':mileage' => $mileage,
	    ]);

        $_SESSION['success'] = 'Record Added';
        $_SESSION['color'] = 'green';

        header('Location: view.php');
    	return;
    }
}



if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
	}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Arpit Jain</title>
    </head>
    <body>
        <div class="container">
            <h1>Tracking Autos for <?php echo $name; ?></h1>
			<?php
                if ( $status !== false ) 
                {
                    // Look closely at the use of single and double quotes
                    echo(
                        '<p style="color: ' .$status_color. ';" class="col-sm-10 col-sm-offset-2">'.
                            htmlentities($status).
                        "</p>\n"
                    );
                }
            ?>
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
                        <input class="form-control" type="text" name="make" id="make">
                    </div>
                </div>
				 <div class="form-group">
                    <label class="control-label col-sm-2" for="year">Model:</label>
                    <div class="col-sm-3">
                        <input class="form-control" type="text" name="model" id="model">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="year">Year:</label>
                    <div class="col-sm-3">
                        <input class="form-control" type="text" name="year" id="year">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="mileage">Mileage:</label>
                    <div class="col-sm-3">
                        <input class="form-control" type="text" name="mileage" id="mileage">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-2 col-sm-offset-2">
					 <input class="btn btn-primary" type="submit" value="Add">
                        <input class="btn" type="submit" name="logout" value="Cancel">
                    </div>
                </div>
            </form>

        </div>
    </body>
</html>
              

