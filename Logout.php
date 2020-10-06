 
<?php

session_start();  // start PHP session! 
?> 
<?php include "Common/Header.php" ?>

<?php

//$valid = false;
//$_SESSION["valid"] = $valid;
session_destroy();

header("Location: Index.php");
?>




<?php include "Common/Footer.php" ?>