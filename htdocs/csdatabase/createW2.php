<?php
    include('db_connect.php');

    $TID = $SID = $W2Year = '';
    $errors = array('TID' => '', 'SID' => '', 'W2Year' => '');

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

        if(empty($_POST['SID'])){
            $errors['SID'] = 'A \'SID\' is required <br />';
        }
        else{
            $SID = $_POST['SID'];
            if(!preg_match('/^[0-9]{20}$/', $SID)){ //{#} is the length it will match
                $errors['SID'] = 'SID must only be numbers with a length of 20 <br />';
            }
        }

        if(empty($_POST['W2Year'])){
            $errors['W2Year'] = 'A \'Year\' is required <br />';
        }
        else{
            $W2Year = $_POST['W2Year'];
            if(!preg_match('/^([0-9]+(\.[0-9]*){0,1})*$/', $W2Year)){
                $errors['W2Year'] = 'Year must contain only numbers <br />';
            }
        }

        if(array_filter($errors)){

        }
        else { //no errors, so do stuff
            $TID = mysqli_real_escape_string($conn, $_POST['TID']);
            $SID = mysqli_real_escape_string($conn, $_POST['SID']);
            $W2Year = mysqli_real_escape_string($conn, $_POST['W2Year']);
            
            //Check that no W2 exists with the entered TID, SID, and W2Year. If not, then...
            $result = mysqli_query($conn, "SELECT * FROM W2 WHERE TID = '$TID' AND SID = '$SID' AND W2Year = 'W2Year'");
            if (mysqli_num_rows($result) == 0) {

                mysqli_free_result($result);

                //Get info from Employers
                $sql = "SELECT Name as EName, Address as EAddress, EmployerZIP as EZIP 
                FROM Employers 
                WHERE Employers.SID = '$SID'";
                //get results and store em
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
                $EName = $row["EName"];
                $EAddress = $row["EAddress"];
                $EZIP = $row["EZIP"];
                mysqli_free_result($result);
                $row = NULL;

                //Get info from Taxpayer
                $sql = "SELECT FirstName as TName, MiddleInitial as TMiddle, LastName as TLast, ResAddress as TAddress, ResCity as TCity, ResState as TState, ResZIP as TZIP, ResSSN as TSSN 
                FROM Taxpayer 
                WHERE Taxpayer.TID = '$TID'";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
                $TName = $row["TName"];
                $TMiddle = $row["TMiddle"];
                $TLast = $row["TLast"];
                $TAddress = $row["TAddress"];
                $TCity = $row["TCity"];
                $TState = $row["TState"];
                $TZIP = $row["TZIP"];
                $TSSN = $row["TSSN"];
                mysqli_free_result($result);
                $row = NULL;
                
                //(Sum of all Amount from Earnings matching TID == TID and W2Year == TaxYear) -> WagesTipsEtc.

                $sql = "SELECT SUM(Amount) as WagesTipsEtc
                FROM Earnings 
                WHERE Earnings.TID = '$TID' AND TaxYear = '$W2YEAR'";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
                $WagesTipsEtc = $row["WagesTipsEtc"];
                mysqli_free_result($result);
                $row = NULL;

                //(Sum of all Withheld from Earnings matching TID == TID and W2Year == TaxYear) -> FedIncTax.

                $sql = "SELECT SUM(Withheld) as FedIncTax
                FROM Earnings 
                WHERE Earnings.TID = '$TID' AND TaxYear = '$W2YEAR'";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
                $FedIncTax = $row["FedIncTax"];
                mysqli_free_result($result);
                $row = NULL;

            
                $SSWages = $SSTax = $MedicareWages = $MedicareTax = $SSTips = $AllocatedTips = $DependentCareBenefits = $StateWagesTipsEtc = $StateIncomeTax = $LocalWagesTipsEtc = $LocalIncomeTax = $LocalityName = ''; //Most of these are beyond the scope of this project, and it's not unusual for many of these to be blank in real-world W2s for many people anyways.

                //Then INSERT all of these into W2. 
                $sql = "INSERT INTO W2
                        VALUES ('$TID', '$SID', '$W2Year', '$EName', '$EAddress', '$EZIP', '$TFirst', '$TMiddle', '$TLast', '$TAddress', '$TCity', '$TState', '$TZIP', '$TSSN', '$WagesTipsEtc', '$FedIncTax', '$SSWages', '$SSTax', '$MedicareWages', '$MedicareTax', '$SSTips', '$AllocatedTips', '$DependentCareBenefits', '$StateWagesTipsEtc', '$StateIncomeTax', '$LocalWagesTipsEtc', '$LocalIncomeTax', '$LocalityName')";
                mysqli_query($conn, $sql);

                //Then dump the results onto the page.
                $sql = "SELECT * FROM W2 WHERE TID = '$TID' AND SID = '$SID' AND W2Year = 'W2Year'";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
                print_r($row);
                mysqli_free_result($result);
                $row = NULL;
                
                //TODO: Page where you can lookup and display already-created W2s?
            }
            else {
                //entry already exists!
            }

        }
    }
    
?>

<!DOCTYPE html>
<html>

    <?php include('templates/header.php'); ?>

    <section class="container grey-text">
        <h4 class="center">W-2 Form</h4>
        <form class="white" action="createW2.php" method="POST">
            <label>Personal TID</label>
            <input type="text" name="TID" value="<?php echo htmlspecialchars($TID)?>">
            <div class="red-text"><?php echo $errors['TID']; ?></div>

            <label>Source ID </label>
            <input type="text" name="SID" value="<?php echo htmlspecialchars($SID)?>">
            <div class="red-text"><?php echo $errors['SID']; ?></div>

            <label>W2 Year</label>
            <input type="text" name="W2Year" value="<?php echo htmlspecialchars($W2Year)?>">
            <div class="red-text"><?php echo $errors['W2Year']; ?></div>
            
            <div class="center">
                <input type="submit" name="submit" value ="submit" 
                class="btn buttonColor z-depth-0">
            </div>
        </form>
    </section>

    <?php include('templates/footer.php'); ?>

</html>