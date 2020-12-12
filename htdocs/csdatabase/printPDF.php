<?php
    session_start();
    include('db_connect.php');
    $TID = $TRYear = '';

    if(isset($_POST['submit'])){

        //error handling
        if(empty($_POST['TID'])){
            $errors['TID'] = 'A \'Personal TID Number\' is required <br />';
        }
        else{
            $TID = $_POST['TID'];
            if(!preg_match('/^[0-9]{20}$/', $TID)){ //{#} is the length it will match
                $errors['TID'] = 'TID must only be numbers with a length of 20 <br />';
            }
        }

        if(empty($_POST['TRYear'])){
            $errors['TRYear'] = 'A \'Year\' is required <br />';
        }
        else{
            $TRYear = $_POST['TRYear'];
            if(!preg_match('/^([0-9]+(\.[0-9]*){0,1})*$/', $TRYear)){
                $errors['TRYear'] = 'Year must contain only numbers <br />';
            }
        }

        if(array_filter($errors)){
            //print errors
        }
        else { //no errors, so do stuff
    
            $TID = mysqli_real_escape_string($conn, $_POST['TID']);
            $_SESSION['TID']=$TID;
            $TRYear = mysqli_real_escape_string($conn, $_POST['TRYear']);
            $_SESSION['TRYEAR']=$TRYEAR;

            $result = mysqli_query($conn, "SELECT * FROM taxreturn WHERE TID = '$TID' AND TRYear = '$TRYear'");

            if(!$result){
                echo "Error@484: " . mysqli_error($conn);
            }else{
                //echo mysqli_num_rows($result);
            }
            //fill array with query results
            $completedTR = array();
            while($curr_row = mysqli_fetch_assoc($result)){
                array_push($completedTR, $curr_row);
            }

            $_SESSION['completedTR'] = $completedTR;
          
            mysqli_close($conn);
        }
    }
?>

<!DOCTYPE HTML>
<html>
    <?php include('templates/header.php'); ?>

    <section class="container grey-text">
        <h4 class="center">Select Tax Return</h4>
        <form class="white" action="printPDF.php" method="POST">
            <label>Personal TID</label>
            <input type="text" name="TID" value="<?php echo htmlspecialchars($TID)?>">
            <div class="red-text"><?php echo $errors['TID']; ?></div>

            <label>Tax Return Year</label>
            <input type="text" name="TRYear" value="<?php echo htmlspecialchars($TRYear)?>">
            <div class="red-text"><?php echo $errors['TRYear']; ?></div>

            <div class="center">
                <input type="submit" name="submit" value ="submit" 
                class="btn buttonColor z-depth-0">
            </div>

            <?php 
                if(isset($_POST['submit'])){
                    ?>
                    <div class="center">
                        <ul>
                        <a class="btn buttonColor z-depth-0" href="getTaxReturn.php"> Print Results </a>
                        </ul>
                    </div>
                    <?php
                } ?>
        </form>
    </section>

    <?php include('templates/footer.php'); ?>

</html>