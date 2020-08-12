<?php
/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
$mysqli = mysqli_connect("localhost", "root", "rebreb08", "mdcsenior_db");

// Check connection
if($mysqli === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Escape user inputs for security
$term = mysqli_real_escape_string($mysqli, $_REQUEST['term']);

if(isset($term)){
    // Attempt select query execution
    $sql = "SELECT DISTINCT sanit_mdname FROM db_sanitation WHERE sanit_mdname LIKE '%" . $term . "%'";
    echo "<div style='max-height:200px; overflow-y:auto'>";
    if($result = mysqli_query($mysqli, $sql)){
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_array($result)){
                echo "<p>" . $row['sanit_mdname'] . "</p>";
            }
            // Close result set
            mysqli_free_result($result);
        } else{
            echo "<p>No matches found</p>";
        }
    } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($mysqli);
    }
    echo "</div>";
}

// close connection
mysqli_close($mysqli);

?>
