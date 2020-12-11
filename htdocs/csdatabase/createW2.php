<?php
    include('db_connect.php');
    $completedW2 = null; // place completed W2 in this variable, if not null, print HTML at end of file

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
            //Doesn't work? nbd
            $result = mysqli_query($conn, "SELECT * FROM w2 WHERE TID = '$TID' AND SID = '$SID' AND W2Year = '$W2Year'");
            if(!$result) {
                echo "querying mytax3.w2 failed, check query syntax in createW2.php";
            }

            if (mysqli_num_rows($result) == 0) {

                mysqli_free_result($result);

                //Get info from Employers
                //Row offset doesn't work
                $sql = "SELECT Name, Address, EmployerZIP FROM employers WHERE SID = '$SID'";
                //get results and store em
                $result = mysqli_query($conn, $sql);
                $row = array();
                while($row_curr = mysqli_fetch_assoc($result)){
                    array_push($row, $row_curr);
                }
                //if (array_key_exists('EName', $row) && !empty($row['EName']))
                $EName = $row[0]['Name'];
                //if (array_key_exists('EAddress', $row) && !empty($row['EAddress']))
                $EAddress = $row[0]["Address"];
                //if (array_key_exists('EZIP', $row) && !empty($row['EZIP']))
                $EZIP = $row[0]["EmployerZIP"];
                mysqli_free_result($result);
                $row = NULL;

                //Get info from Taxpayer
                //$result is a boolean
                $sql = "SELECT FirstName as TFirst, MiddleInitial as TMiddle, LastName as TLast, ResAddress as TAddress, ResCity as TCity, ResState as TState, ResZIP as TZIP, ResSSN as TSSN FROM taxpayer WHERE TID = '$TID'";
                $result = mysqli_query($conn, $sql);
                $row = array();
                while($row_curr = mysqli_fetch_assoc($result)){
                    array_push($row, $row_curr);
                }
                $TFirst = $row[0]["TFirst"];
                $TMiddle = $row[0]["TMiddle"];
                $TLast = $row[0]["TLast"];
                $TAddress = $row[0]["TAddress"];
                $TCity = $row[0]["TCity"];
                $TState = $row[0]["TState"];
                $TZIP = $row[0]["TZIP"];
                $TSSN = $row[0]["TSSN"];
                mysqli_free_result($result);
                $row = NULL;
                
                //(Sum of all Amount from Earnings matching TID == TID and W2Year == TaxYear) -> WagesTipsEtc.
                //Row offset doesn't work.
                $sql = "SELECT SUM(Amount) as WagesTipsEtc FROM earnings WHERE earnings.TID = '$TID' AND TaxYear = '$W2Year'";
                $result = mysqli_query($conn, $sql);
                // check results of query
                if(!$result) {
                    echo "querying mytax3.w2 failed, check query syntax in createW2.php @ 102";
                }
                // store in array
                $row = array();
                while($row_curr = mysqli_fetch_assoc($result)){
                    array_push($row, $row_curr);
                }
                //if (array_key_exists('WagesTipsEtc', $row) && !empty($row['WagesTipsEtc']))
                    $WagesTipsEtc = $row[0]['WagesTipsEtc'];
                    //print_r($WagesTipsEtc);
                    //var_dump($WagesTipsEtc);
                    //var_dump($row);
                mysqli_free_result($result);
                $row = NULL;

                //(Sum of all Withheld from Earnings matching TID == TID and W2Year == TaxYear) -> FedIncTax.
                //$result is a boolean
                $sql = "SELECT SUM(Withheld) as FedIncTax FROM earnings WHERE TID = $TID AND TaxYear = $W2Year";
                $result = mysqli_query($conn, $sql);

                if(!$result) {
                    echo "querying mytax3.w2 failed, check query syntax in createW2.php @ 123";
                }

                $row = array();
                while($row_curr = mysqli_fetch_assoc($result)){
                    array_push($row, $row_curr);
                }
                $FedIncTax = $row[0]["FedIncTax"];
                mysqli_free_result($result);
                $row = NULL;

            
                $SSWages = $SSTax = $MedicareWages = $MedicareTax = $SSTips = $AllocatedTips = $DependentCareBenefits = $StateWagesTipsEtc = $StateIncomeTax = $LocalWagesTipsEtc = $LocalIncomeTax = $LocalityName = 0; //Most of these are beyond the scope of this project, and it's not unusual for many of these to be blank in real-world W2s for many people anyways.

                //Then INSERT all of these into W2. 
                $sql = "INSERT INTO w2 VALUES ('$TID', '$SID', '$W2Year', '$EName', '$EAddress', '$EZIP', '$TFirst', '$TMiddle', '$TLast', '$TAddress', '$TCity', '$TState', '$TZIP', '$TSSN', '$WagesTipsEtc', '$FedIncTax', '$SSWages', '$SSTax', '$MedicareWages', '$MedicareTax', '$SSTips', '$AllocatedTips', '$DependentCareBenefits', '$StateWagesTipsEtc', '$StateIncomeTax', '$LocalWagesTipsEtc', '$LocalIncomeTax', '$LocalityName')";
                $result = mysqli_query($conn, $sql); // WARNING: This might not be good..

                if(!$result) {
                    echo "insert mytax3.w2 failed, check query syntax in createW2.php @ 142";
                    printf("Error: %s\n", mysqli_error($conn));
                }

                //Then dump the results onto the page.
                $sql = "SELECT * FROM w2 WHERE TID = '$TID' AND SID = '$SID' AND W2Year = '$W2Year'";
                $result = mysqli_query($conn, $sql);
                // check that query succeeded print error otherwise
                if(!$result){
                    //echo 'error, query failed';
                    //printf("Error: %s\n", mysqli_error($conn));
                }

                $completedW2 = array();
                while($row_curr = mysqli_fetch_assoc($result)){
                    array_push($completedW2, $row_curr);
                }
                mysqli_free_result($result);
                $row = NULL;
                
                //TODO: Page where you can lookup and display already-created W2s?
                mysqli_close($conn);
            }
            else {
                echo "W2 already exists!";
                $sql = "SELECT * FROM w2 WHERE TID = '$TID' AND SID = '$SID' AND W2Year = '$W2Year'";
                $result = mysqli_query($conn, $sql);

                $completedW2 = array();
                while($row_curr = mysqli_fetch_assoc($result)){
                    array_push($completedW2, $row_curr);
                }
                mysqli_free_result($result);
                $row = NULL;
                
                //TODO: Page where you can lookup and display already-created W2s?
                mysqli_close($conn); 
            }

        }
    }
    
?>

<head>
    <style type="text/css">
        .label-text{
            /*width: 75%;
            border-radius: 5px;
            padding: 5px;*/
            font-weight:bold;
            /*background: #E27D60  !important;*/
        }

        .label-user-header{
            background: #8c8c8c;
            padding: 30px;
            border-radius: 3px;
            text-align: center;
            vertical-align: middle;
        }

        .flex-container{
            display: flex;
        }

        .flex-child{
            flex: 1;
        }
    </style>
</head>

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

    <section class="container grey-text">
        <h4 class="center">Completed Tax Return</h4>
        <form class="white" action="createW2.php" method="POST">
            <?php 
                foreach($completedW2 as $row_curr){
                    //array_push($row, $row_curr);
                    ?>

                    <html>
                        <div class="col s6">
                            <div class="card z-depth-0">
                                <div class="card-content center">
                                    <h5 class="label-user-header">
                                        <font color="black">
                                            <?php echo htmlspecialchars($row_curr['TFirst'] . " " . $row_curr['TLast']); ?>
                                        </font>
                                    </h5>
                                    <?php foreach ($row_curr as $key => &$value) { ?>
                                    <div class="flex-container">
                                        <div class="flex-child label-text" align = "left"> 
                                            <font color="black">
                                                <?php echo htmlspecialchars($key);?>
                                            </font>
                                        </div>
                                        <div  class="flex-child" align = "right"> 
                                            <?php echo htmlspecialchars($value);?>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </html>
            <?php } ?>
        </form>
        </form>
    </section>

    <?php include('templates/footer.php'); ?>

</html>