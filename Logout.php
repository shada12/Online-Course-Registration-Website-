 
<?php

session_start();  // start PHP session! 
?> 
<?php include "Lab5Common/Header.php" ?>

<?php

//$valid = false;
//$_SESSION["valid"] = $valid;
session_destroy();

header("Location: http://localhost:8080/CST8257Lab5/Index.php");
?>




<?php include "Lab5Common/Footer.php" ?>