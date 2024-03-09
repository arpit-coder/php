<?php

session_start();


if ( ! isset($_SESSION['name']) ) {
	die('name parameter required');
}





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
$name = htmlentities($_SESSION['name']);

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
            <p>
				<a href="add.php" class="btn btn-primary">Add New Entry</a>
				<a href="autoscrud.php" class="btn btn-default">Logout</a>
            </p>

           
                <h2>Automobiles</h2>
               
                    
                        <?php echo('<table border="1">');
						    $stmt = $pdo->query("SELECT make, model, year, mileage, auto_id FROM autos");
                            while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
      						echo"<tr><td>";
                             echo (htmlentities($row['make'])); 
							echo ("</td><td>");
							 echo (htmlentities($row['model'])); 
							echo ("</td><td>"); 
							 echo (htmlentities($row['year']));  
							echo ("</td><td>"); 
							 echo (htmlentities($row['mileage'])); 
							 echo ("</td><td>");
                             echo('<a href="edit.php?auto_id='.$row['auto_id'].'">EDIT</a>/'); 
						     echo('<a href="delete.php?auto_id='.$row['auto_id'].'">DELETE</a>'); 
						    echo ("</td></tr>"); 
							}
						?>
						</table>
                    

        </div>
    </body>
</html>

