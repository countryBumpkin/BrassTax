<?php
    include('db_connect.php');
    $completedTR = null;

    $TID = $TRYear = $FilingStatus = $SpouseTID = $SpouseBlind = $AmBlind = $AmWoundedArmedForces = '';
    $errors = array('TID' => '', 'TRYear' => '', 'FilingStatus' => '', 'SpouseTID' => '', 'SpouseBlind' => '', 'AmBlind' => '', 'AmWoundedArmedForces' => '');

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

        if(!empty($_POST['SpouseTID'])){
            $SpouseTID = $_POST['SpouseTID'];
            if(!preg_match('/^[0-9]{20}$/', $SpouseTID)){ //{#} is the length it will match
                $errors['SpouseTID'] = 'SpouseTID must only be blank or numbers with a length of 20 <br />';
            }
        }else{
            $SpouseTID = 0;
        }

        if(array_filter($errors)){
            //print errors
        }
        else { //no errors, so do stuff
            $TID = mysqli_real_escape_string($conn, $_POST['TID']);
            $TRYear = mysqli_real_escape_string($conn, $_POST['TRYear']);

            /*
            Entered on this page:
            TID
            TRYear
            FilingStatus (Single, MarriedFilingJointly, MarriedFilingSeparately)
            SpouseTID (Blank unless MarriedFilingJointly)
            SpouseBlind (Blank unless MarriedFilingJointly, otherwise Yes/No)
            AmBlind (Yes/No)
            AmWoundedArmedForces (Yes/No)
            
            ResFCountry, ResFProvince, ResFPostalCode, AmDependent, SpouseDependent, SpouseItemizesOrDualStatus need to be entered on this page. AmDependent cannot be blank (Yes/No), and if there is a Spouse, SpouseDependent (Yes/No) cannot be blank either. //These are outside the scope of this project and will be left at default values.
            */
            $ResFCountry = $ResFProvince = $ResFPostalCode = $AmDependent = $SpouseDependent = $SpouseItemizesOrDualStatus = '';

            /*
            -----------------------------------

            Calculated:
            */

            //Check that no TaxReturn exists with the entered TID and TRYear. If not, then...

            //Taxpayer basic info
            $sql = "SELECT FirstName, MiddleInitial, LastName, Age, Sex, DoB, ResSSN as SSN, ResAddress, ResAptNo, ResCity, ResState, ResZIP FROM taxpayer WHERE TID = '$TID'";
            $result = mysqli_query($conn, $sql);
            if(!$result){
                echo "Error: " . mysqli_error($conn);
            }
            //fill array with query results
            $row = array();
            while($curr_row = mysqli_fetch_assoc($result)){
                array_push($row, $curr_row);
            }
            //print(" Taxpayer basic info: ");
            //print_r($row);
            if (array_key_exists('FirstName', $row[0]) && !empty($row[0]['FirstName']))
                $FirstName = $row[0]["FirstName"];
            else
                $FirstName = '';
            if (array_key_exists('MiddleInitial', $row[0]) && !empty($row[0]['MiddleInitial']))
                $MiddleInitial = $row[0]["MiddleInitial"];
            else
                $MiddleInitial = '';
            if (array_key_exists('LastName', $row[0]) && !empty($row[0]['LastName']))
                $LastName = $row[0]["LastName"];
            else
                $LastName = '';
            if (array_key_exists('Age', $row[0]) && !empty($row[0]['Age']))
                $Age = $row[0]["Age"];
            else
                $Age = '';
            if (array_key_exists('Sex', $row[0]) && !empty($row[0]['Sex']))
                $Sex = $row[0]["Sex"];
            else
                $Sex = '';
            if (array_key_exists('DoB', $row[0]) && !empty($row[0]['DoB']))
                $DoB = $row[0]["DoB"];
            else
                $DoB = '';
            if (array_key_exists('SSN', $row[0]) && !empty($row[0]['SSN']))
                $SSN = $row[0]["SSN"];
            else
                $SSN = '';
            if (array_key_exists('ResAddress', $row[0]) && !empty($row[0]['ResAddress']))
                $ResAddress = $row[0]["ResAddress"];
            else
                $ResAddress = '';
            if (array_key_exists('ResAptNo', $row[0]) && !empty($row[0]['ResAptNo']))
                $ResAptNo = $row[0]["ResAptNo"];
            else
                $ResAptNo = '';
            if (array_key_exists('ResCity', $row[0]) && !empty($row[0]['ResCity']))
                $ResCity = $row[0]["ResCity"];
            else
                $ResCity = '';
            if (array_key_exists('ResState', $row[0]) && !empty($row[0]['ResState']))
                $ResState = $row[0]["ResState"];
            else 
                $ResState = '';
            if (array_key_exists('ResZIP', $row[0]) && !empty($row[0]['ResZIP']))
                $ResZIP = $row[0]["ResZIP"];
            else
                $ResZIP = '';
            mysqli_free_result($result);
            $row = NULL;

            //Spouse basic info
            if ($SpouseTID != '' and $FilingStatus == "MarriedFilingJointly") {
                $sql = "SELECT FirstName as SpouseFirst, MiddleInitial as SpouseMiddle, LastName as SpouseLast, ResSSN as SpouseSSN, DoB as SpouseDoB FROM taxpayer WHERE TID = '$SpouseTID'";
                $result = mysqli_query($conn, $sql);
                if(!$result){
                echo "Error@135: " . mysqli_error($conn);
                }
                //fill array with query results
                $row = array();
                while($curr_row = mysqli_fetch_assoc($result)){
                    array_push($row, $curr_row);
                }
                //print(" Spouse basic info: ");
                //print_r($row);
                if (array_key_exists('SpouseFirst', $row[0]) && !empty($row[0]['SpouseFirst']))
                    $SpouseFirst = $row[0]["SpouseFirst"];
                else
                    $SpouseFirst = '';
                if (array_key_exists('SpouseMiddle', $row[0]) && !empty($row[0]['SpouseMiddle']))
                    $SpouseMiddle = $row[0]["SpouseMiddle"];
                else
                    $SpouseMiddle = '';
                if (array_key_exists('SpouseLast', $row[0]) && !empty($row[0]['SpouseLast']))
                    $SpouseLast = $row[0]["SpouseLast"];
                else
                    $SpouseLast = '';
                if (array_key_exists('SpouseSSN', $row[0]) && !empty($row[0]['SpouseSSN']))
                    $SpouseSSN = $row[0]["SpouseSSN"];
                else
                    $SpouseSSN = 0;
                if (array_key_exists('SpouseDoB', $row[0]) && !empty($row[0]['SpouseDoB']))
                    $SpouseDoB = $row[0]["SpouseDoB"];
                else
                    $SpouseDoB = '';
                mysqli_free_result($result);
                $row = NULL;
            }
            else {
                $SpouseFirst = $SpouseMiddle = $SpouseLast = $SpouseSSN = $SpouseDoB = 0;
            }

            //BornBefore1955
            if ($DoB < "1995-01-02") 
                $BornBefore1955 = 'Yes';
            elseif ($DoB > "1995-01-02")
                $BornBefore1955 = 'No';
            else
                $BornBefore1955 = '';

            //SpouseBornBefore1955
            if ($SpouseTID != '') {
                if ($SpouseDoB < "1995-01-02") 
                    $SpouseBornBefore1955 = 'Yes';
                else
                    $SpouseBornBefore1955 = 'No';
            }
            else
                $SpouseBornBefore1955 = '';
            
            //Wages
            $sql = "SELECT SUM(WagesTipsEtc) AS Wages FROM w2 WHERE W2Year = '$TRYear' AND TID = '$TID'";
            $result = mysqli_query($conn, $sql);
            if(!$result){
                echo "Error@193: " . mysqli_error($conn);
            }
            //fill array with query results
            $row = array();
            while($curr_row = mysqli_fetch_assoc($result)){
                array_push($row, $curr_row);
            }
            //print(" WagesTipsEtc: ");
            //print_r($row);
            if (array_key_exists('Wages', $row[0]) && !empty($row[0]['Wages']))
                $Wages = $row[0]["Wages"];
            else
                $Wages = '0';
            mysqli_free_result($result);
            $row = NULL;

            //TaxExemptInterest
            $sql = "SELECT SUM(Amount) AS TaxExemptInterest FROM expenses WHERE TID = '$TID' AND TaxCategory = 'HomeMortgageInterests'";
            $result = mysqli_query($conn, $sql);
            if(!$result){
                echo "Error@213: " . mysqli_error($conn);
            }
            //fill array with query results
            $row = array();
            while($curr_row = mysqli_fetch_assoc($result)){
                array_push($row, $curr_row);
            }
            //print(" TaxExemptInterest: ");
            //print_r($row);
            if (array_key_exists('TaxExemptInterest', $row[0]) && !empty($row[0]['TaxExemptInterest']))
                $TaxExemptInterest = $row[0]["TaxExemptInterest"];
            else
                $TaxExemptInterest = '0';
            mysqli_free_result($result);
            $row = NULL;

            //Out of scope
            $TaxableInterest = '0';

            $QualifiedDividends = $OrdinaryDividends = $IRADistributions = $IRATaxable = $PensionsAndAnnuities = $TaxablePensionsAndAnnuities = $SocialSecurityBenefits = $TaxableSSB = $CapitalGainLoss = '0'; 
            
            $CapitalGainLossNotRequired = '';

            //OtherIncome
            $sql = "SELECT SUM(Amount) AS SumEarnings FROM earnings WHERE TID = '$TID' AND TaxYear = '$TRYear'";
            $result = mysqli_query($conn, $sql);
            if(!$result){
                echo "Error@240: " . mysqli_error($conn);
            }
            //fill array with query results
            $row = array();
            while($curr_row = mysqli_fetch_assoc($result)){
                array_push($row, $curr_row);
            }
            //print(" OtherIncome: ");
            //print_r($row);
            if (array_key_exists('SumEarnings', $row[0]) && !empty($row[0]['SumEarnings']))
                $SumEarnings = $row[0]["SumEarnings"];
            else
                $SumEarnings = '0';
            mysqli_free_result($result);
            $row = NULL;

            $sql = "SELECT SUM(Amount) AS SumRental FROM rentalearnings WHERE TID = '$TID' AND TaxYear = '$TRYear'";
            $result = mysqli_query($conn, $sql);
            if(!$result){
                echo "Error@259: " . mysqli_error($conn);
            }
            //fill array with query results
            $row = array();
            while($curr_row = mysqli_fetch_assoc($result)){
                array_push($row, $curr_row);
            }
            //print(" OtherIncome2: ");
            //print_r($row);
            if (array_key_exists('SumRental', $row[0]) && !empty($row[0]['SumRental']))
                $SumRental = $row[0]["SumRental"];
            else
                $SumRental = '0';
            mysqli_free_result($result);
            $row = NULL;

            $OtherIncome = $SumEarnings + $SumRental;

            //More calculations
            $TotalIncome = $Wages + $TaxableInterest + $OrdinaryDividends + $IRATaxable + $TaxablePensionsAndAnnuities + $TaxableSSB + $CapitalGainLoss + $OtherIncome;

            //Out of scope, set to 0.
            $AdjustmentsToIncome = '0';

            $AdjustedGrossIncome = $TotalIncome - $AdjustmentsToIncome;

            //StandardDeduction
            if ($AmWoundedArmedForces == 'Yes')
                $StandardDeduction = '5000';
            elseif ($AmBlind = 'Yes')
                $StandardDeduction = '3750';
            elseif ($Sex == 'Female' || $Age >= 65)
                $StandardDeduction = '3500';
            else
                $StandardDeduction = '3000';

            $QualifiedBusinessIncomeDeduction = '0';

            $SumStdDeductAndQualifiedBusn = $StandardDeduction + $QualifiedBusinessIncomeDeduction;

            //OtherTaxDeductibleExpenses
            $sql = "SELECT SUM(Amount) AS SumTaxDeductibleExpenses FROM expenses WHERE TID = '$TID' AND TaxYear = '$TRYear' AND (TaxCategory = 'StudentLoanPlusInterest' OR TaxCategory = 'RentalRepair')";
            $result = mysqli_query($conn, $sql);
            if(!$result){
                echo "Error@303: " . mysqli_error($conn);
            }
            //fill array with query results
            $row = array();
            while($curr_row = mysqli_fetch_assoc($result)){
                array_push($row, $curr_row);
            }
            //print(" OtherTaxDeductibleExpenses: ");
            //print_r($row);
            if (array_key_exists('SumTaxDeductibleExpenses', $row[0]) && !empty($row[0]['SumTaxDeductibleExpenses']))
                $SumTaxDeductibleExpenses = $row[0]["SumTaxDeductibleExpenses"];
            else
                $SumTaxDeductibleExpenses = '0';
            mysqli_free_result($result);
            $row = NULL;
            
            $sql = "SELECT SUM(Amount) AS SumTaxDeductibleEmplExpenses FROM employmentexpenses WHERE TID = '$TID' AND TaxYear = '$TRYear'";
            $result = mysqli_query($conn, $sql);
            if(!$result){
                echo "Error@322: " . mysqli_error($conn);
            }
            //fill array with query results
            $row = array();
            while($curr_row = mysqli_fetch_assoc($result)){
                array_push($row, $curr_row);
            }
            //print(" SumTaxDeductibleEmplExpenses: ");
            //print_r($row);
            if (array_key_exists('SumTaxDeductibleEmplExpenses', $row[0]) && !empty($row[0]['SumTaxDeductibleEmplExpenses']))
                $SumTaxDeductibleEmplExpenses = $row[0]["SumTaxDeductibleEmplExpenses"];
            else
                $SumTaxDeductibleEmplExpenses = '0';
            mysqli_free_result($result);
            $row = NULL;

            $OtherTaxDeductibleExpenses = $SumTaxDeductibleExpenses + $SumTaxDeductibleEmplExpenses;

            //TotalWithheldAlready
            $sql = "SELECT SUM(Withheld) AS WithheldEarnings FROM earnings WHERE TID = '$TID' AND TaxYear = '$TRYear'";
            $result = mysqli_query($conn, $sql);
            if(!$result){
                echo "Error@344: " . mysqli_error($conn);
            }
            //fill array with query results
            $row = array();
            while($curr_row = mysqli_fetch_assoc($result)){
                array_push($row, $curr_row);
            }
            //print(" WithheldEarnings: ");
            //print_r($row);
            if (array_key_exists('WithheldEarnings', $row[0]) && !empty($row[0]['WithheldEarnings']))
                $WithheldEarnings = $row[0]["WithheldEarnings"];
            else
                $WithheldEarnings = '0';
            mysqli_free_result($result);
            $row = NULL;

            $sql = "SELECT SUM(TaxWithheld) AS WithheldEmpEarnings FROM employmentearnings WHERE TID = '$TID' AND TaxYear = '$TRYear'";
            $result = mysqli_query($conn, $sql);
            if(!$result){
                echo "Error363: " . mysqli_error($conn);
            }
            //fill array with query results
            $row = array();
            while($curr_row = mysqli_fetch_assoc($result)){
                array_push($row, $curr_row);
            }
            //print(" WithheldEmpEarnings: ");
            //print_r($row);
            if (array_key_exists('WithheldEmpEarnings', $row[0]) && !empty($row[0]['WithheldEmpEarnings']))
                $WithheldEmpEarnings = $row[0]["WithheldEmpEarnings"];
            else
                $WithheldEmpEarnings = '0';
            mysqli_free_result($result);
            $row = NULL;

            $sql = "SELECT SUM(TaxWithheld) AS WithheldRentEarnings FROM rentalearnings WHERE TID = '$TID' AND TaxYear = '$TRYear'";
            $result = mysqli_query($conn, $sql);
            if(!$result){
                echo "Error@382: " . mysqli_error($conn);
            }
            //fill array with query results
            $row = array();
            while($curr_row = mysqli_fetch_assoc($result)){
                array_push($row, $curr_row);
            }
            //print(" WithheldRentEarnings: ");
            //print_r($row);
            if (array_key_exists('WithheldRentEarnings', $row[0]) && !empty($row[0]['WithheldRentEarnings']))
                $WithheldRentEarnings = $row[0]["WithheldRentEarnings"];
            else
                $WithheldRentEarnings = '0';
            mysqli_free_result($result);
            $row = NULL;

            $TotalWithheldAlready = $WithheldEarnings + $WithheldEmpEarnings + $WithheldRentEarnings;

            //TaxableIncome
            $TaxableIncome = $AdjustedGrossIncome - $SumStdDeductAndQualifiedBusn - $OtherTaxDeductibleExpenses;

            //$ATITaxes
            //print($TaxableIncome);
            $tempTaxableIncome = $TaxableIncome;
            //echo " "; 
            //echo $tempTaxableIncome;
            $ATITaxes = 0;
            while($tempTaxableIncome != 0) {
                if ($tempTaxableIncome > 35000) {
                    $temp = $tempTaxableIncome - 35000;
                    $ATITaxes += ($temp * 0.3);
                    $tempTaxableIncome = 35000;
                }
                elseif (($tempTaxableIncome > 18000)) {
                    $temp = $tempTaxableIncome - 18000;
                    $ATITaxes += ($temp * 0.25);
                    $tempTaxableIncome = 18000;
                }
                elseif (($tempTaxableIncome > 11000)) {
                    $temp = $tempTaxableIncome - 11000;
                    //print " temp over 11000: ";
                    //echo $temp;
                    $ATITaxes += ($temp * 0.2);
                    //print " ATITaxes: ";
                    //echo $ATITaxes;
                    $tempTaxableIncome = 11000;
                }
                elseif (($tempTaxableIncome > 5000)) {
                    $temp = $tempTaxableIncome - 5000;
                    //print " temp over 5000: ";
                    //echo $temp;
                    $ATITaxes += ($temp * 0.15);
                    //print " ATITaxes: ";
                    //echo $ATITaxes;
                    $tempTaxableIncome = 5000;
                }
                elseif ($tempTaxableIncome < 0) {
                    $ATITaxes = 0;
                    $tempTaxableIncome = 0;
                }
                else /*($tempTaxableIncome <= 5000)*/ {
                    $temp = $tempTaxableIncome;
                    //print " temp under 5001: ";
                    //echo $temp;
                    $ATITaxes += ($temp * 0.1);
                    //print " ATITaxes: ";
                    //echo $ATITaxes;
                    $tempTaxableIncome = 0;
                }
            }
            /*
            echo " ATITaxes:";
            echo $ATITaxes;
            ceil($ATITaxes); //round up
            echo " ATITaxes:";
            echo $ATITaxes;
            */

            //TotalTaxOwed
            $TotalTaxOwed = $ATITaxes - $TotalWithheldAlready;

            /*
            echo " First Name: ";
            echo $FirstName;
            echo " Wages: ";
            echo $Wages;
            echo " WithheldEarnings: ";
            echo $WithheldEarnings;
            echo " TaxExemptInterest: ";
            echo $TaxExemptInterest;
            echo " TotalWithheldAlready: ";
            echo $TotalWithheldAlready;
            */

            //Then INSERT all of these into TaxReturn.
            $sql = "INSERT INTO taxreturn VALUES ('$TID', '$TRYear', '$FilingStatus', '$FirstName', '$MiddleInitial', '$LastName', '$Age', '$Sex', '$DoB', '$SSN', '$SpouseTID', '$SpouseFirst', '$SpouseMiddle', '$SpouseLast', '$SpouseSSN', '$ResAddress', '$ResAptNo', '$ResCity', '$ResState', '$ResZIP', '$ResFCountry', '$ResFProvince', '$ResFPostalCode', '$AmDependent', '$SpouseDependent', '$SpouseItemizesOrDualStatus', '$BornBefore1955', '$AmBlind', '$SpouseBornBefore1955', '$SpouseBlind', '$Wages', '$TaxExemptInterest', '$TaxableInterest', '$QualifiedDividends', '$OrdinaryDividends', '$IRADistributions', '$IRATaxable', '$PensionsAndAnnuities', '$TaxablePensionsAndAnnuities', '$SocialSecurityBenefits', '$TaxableSSB', '$CapitalGainLoss', '$CapitalGainLossNotRequired', '$OtherIncome', '$TotalIncome', '$AdjustmentsToIncome', '$AdjustedGrossIncome', '$StandardDeduction', '$QualifiedBusinessIncomeDeduction', '$SumStdDeductAndQualifiedBusn', '$OtherTaxDeductibleExpenses', '$TotalWithheldAlready', '$TaxableIncome', '$ATITaxes', '$TotalTaxOwed')";
            $result = mysqli_query($conn, $sql);
            if(!$result){
                //echo "Error@480: " . mysqli_error($conn);
            }

            //Dump the results onto the page.
            $sql = "SELECT * FROM taxreturn WHERE TID = '$TID' AND TRYear = '$TRYear'";
            $result = mysqli_query($conn, $sql);
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

            //print_r($compeletedTR);
            //var_dump($result);
            mysqli_free_result($result);
            $row = NULL;
            
            //TODO: Page where you can query and display already-created TaxReturn entries?
            mysqli_close($conn);
        }
    }
    if(isset($_POST['print_to_pdf'])) {
        echo "Hello World!";
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
        <h4 class="center">Tax Return</h4>
        <form class="white" action="createTaxReturn.php" method="POST">
            <label>Personal TID</label>
            <input type="text" name="TID" value="<?php echo htmlspecialchars($TID)?>">
            <div class="red-text"><?php echo $errors['TID']; ?></div>

            <label>Tax Return Year</label>
            <input type="text" name="TRYear" value="<?php echo htmlspecialchars($TRYear)?>">
            <div class="red-text"><?php echo $errors['TRYear']; ?></div>

            <label>Filing Status (Single, MarriedFilingJointly, MarriedFilingSeparately)</label>
            <input type="text" name="FilingStatus" value="<?php echo htmlspecialchars($FilingStatus)?>">
            <div class="red-text"><?php echo $errors['FilingStatus']; ?></div>

            <label>Spouse TID (Leave blank if Single or MarriedFilingSeparately)</label>
            <input type="text" name="SpouseTID" value="<?php echo htmlspecialchars($SpouseTID)?>">
            <div class="red-text"><?php echo $errors['SpouseTID']; ?></div>

            <label>Is your spouse legally blind? (Yes/No)</label>
            <input type="text" name="SpouseBlind" value="<?php echo htmlspecialchars($SpouseBlind)?>">
            <div class="red-text"><?php echo $errors['SpouseBlind']; ?></div>

            <label>Are you legally blind? (Yes/No)</label>
            <input type="text" name="AmBlind" value="<?php echo htmlspecialchars($AmBlind)?>">
            <div class="red-text"><?php echo $errors['AmBlind']; ?></div>

            <label>Were you wounded while serving in the United States Armed Forces? (Yes/No)</label>
            <input type="text" name="AmWoundedArmedForces" value="<?php echo htmlspecialchars($AmWoundedArmedForces)?>">
            <div class="red-text"><?php echo $errors['AmWoundedArmedForces']; ?></div>

            

            <div class="center">
                <input type="submit" name="submit" value ="submit" 
                class="btn buttonColor z-depth-0">
            </div>
        </form>
    </section>

    <section class="container grey-text">
        <h4 class="center">Completed Tax Returns</h4>
        <form class="white" action="createW2.php" method="POST">
            <?php 
                foreach($completedTR as $row_curr){
                    ?>

                    <html>
                        <div class="col s6">
                            <div class="card z-depth-0">
                                <div class="card-content center">
                                    <h5 class="label-user-header">
                                        <font color="black">
                                            <?php echo htmlspecialchars($row_curr['FirstName'] . " " . $row_curr['LastName']); ?>
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
            <?php }
            $completedTR = NULL;
            ?>
        </form>
        </form>
    </section>

    <?php include('templates/footer.php'); ?>

</html>