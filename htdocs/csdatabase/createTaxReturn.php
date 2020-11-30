<?php
    include('db_connect.php');

    $TID = $TRYear = '';
    $errors = array('TID' => '', 'TRYear' => '');

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
            ResFCountry, ResFProvince, ResFPostalCode, AmDependent, SpouseDependent, SpouseItemizesOrDualStatus need to be entered on this page. AmDependent cannot be blank (Yes/No), and if there is a Spouse, SpouseDependent (Yes/No) cannot be blank either.

            -----------------------------------

            Calculated:

            Check that no TaxReturn exists with the entered TID and TRYear. If not, then...

            FirstName -> FirstName, MiddleInitial -> MiddleInitial, LastName -> LastName, Age -> Age, Sex -> Sex, DoB -> DoB, ResSSN -> SSN, ResAddress -> ResAddress, ResAptNo -> ResAptNo, ResCity -> ResCity, ResState -> ResState, ResZIP -> ResZIP from Taxpayer matching TID == TID.
            
            If SpouseTID != '' and FilingStatus == 'MarriedFilingJointly', then...
            FirstName -> SpouseFirst, MiddleInitial -> SpouseMiddle, LastName -> LastName, ResSSN -> SSN from Taxpayer matching TID == TID.
            Else, they all = ''.

            BornBefore1955 (January 2nd) should be calculated using Taxpayer's DoB (Yes/No).

            if (SpouseTID == '')
                SpouseBornBefore1955 = ''; 
            Else calculated from Spouse's Taxpayer's DoB (Yes/No).

            Wages should be the sum of all WagesTipsEtc from all W2s where TID == TID.

            TaxExemptInterest should be the sum of all Amount in Expenses where TID == TID and TaxCategory == 'HomeMortgageInterests' and TaxYear = TRYear.

            TaxableInterest = '0'; //should be the sum of all Amount in Expenses where TID == TID and TaxCategory == ???. //beyond the scope of this project atm

            $QualifiedDividends = $OrdinaryDividends = $IRADistributions = $IRATaxable = $PensionsAndAnnuities = $TaxablePensionsAndAnnuities = $SocialSecurityBenefits = $TaxableSSB = $CapitalGainLoss = '0'; 
            
            $CapitalGainLossNotRequired = '';

            OtherIncome should be the sum of Amount in Earnings AND RentalEarnings where TID = TID and TRYear = TaxYear.

            $TotalIncome = $Wages + $TaxableInterest + $OrdinaryDividends + $IRATaxable + $TaxablePensions + $TaxableSSB + $CapitalGainLoss + $OtherIncome;

            $AdjustmentsToIncome = '0';

            $AdjustedGrossIncome = $TotalIncome - $AdjustmentsToIncome;

            StandardDeduction is...
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

            OtherTaxDeductibleExpenses should be the sum of Amount in Expenses where TID = TID, TRYear = TaxYear, and TaxCategory = 'StudentLoanPlusInterest' or 'RentalRepair', PLUS the sum of Amount in EmploymentExpenses where TID = TID and TRYear = TaxYear.

            TotalWithheldAlready should be the sum of TaxWithheld from Earnings, EmploymentEarnings, and RentalEarnings, where TID = TID and TRYear = TaxYear.

            $TaxableIncome = $AdjustedGrossIncome - $SumStdDeductAndQualifiedBusn - $OtherTaxDeductibleExpenses

            $ATITaxes uses the graduated scale in the syllabus.

            $TotalTaxOwed = $ATITaxes - $TotalWithheldAlready



            Then INSERT all of these into TaxReturn.

            Then dump the results onto the page.
            
            TODO: Page where you can query and display already-created TaxReturn entries?
            */

        }
    }
    
?>

<!DOCTYPE html>
<html>

    <?php include('templates/header.php'); ?>

    <section class="container grey-text">
        <h4 class="center">Tax Return</h4>
        <form class="white" action="createTaxReturn.php" method="POST">
            <label>Personal TID</label>
            <input type="text" name="TID" value="<?php echo htmlspecialchars($TID)?>">

            <label>Tax Return Year</label>
            <input type="text" name="TRYear" value="<?php echo htmlspecialchars($TRYear)?>">
            <div class="red-text"><?php echo $errors['TRYear']; ?></div>

            <div class="center">
                <input type="submit" name="submit" value ="submit" 
                class="btn buttonColor z-depth-0">
            </div>
        </form>
    </section>

    <?php include('templates/footer.php'); ?>

</html>