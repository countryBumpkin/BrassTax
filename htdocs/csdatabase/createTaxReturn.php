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
            Need the following:

            Check that no TaxReturn exists with the entered TID and TRYear. If not, then...

            FilingStatus needs to be entered on this page.
            
            SpouseTID needs to be entered on this page, and should be able to be blank.

            FirstName -> FirstName, MiddleInitial -> MiddleInitial, LastName -> LastName, ResSSN -> SSN, ResAddress -> ResAddress, ResAptNo -> ResAptNo, ResCity -> ResCity, ResState -> ResState, ResZIP -> ResZIP from Taxpayer matching TID == TID.
            
            If SpouseTID != '', then...
            FirstName -> SpouseFirst, MiddleInitial -> SpouseMiddle, LastName -> LastName, ResSSN -> SSN from Taxpayer matching TID == TID.
            Else, they all = ''.

            ResFCountry, ResFProvince, ResFPostalCode, AmDependent, SpouseDependent, SpouseItemizesOrDualStatus need to be entered on this page. AmDependent cannot be blank, and if there is a Spouse, SpouseDependent cannot be blank either.

            BornBefore1955 should be calculated using Taxpayer's DoB.

            AmBlind needs to be entered on this page.

            SpouseBornBefore1955 should be blank if no SpouseTID. Else calculated from Spouse's Taxpayer's DoB.

            SpouseBlind may be blank if no SpouseTID. Else should be entered.

            Wages should be the sum of all WagesTipsEtc from all W2s where TID == TID.

            TaxExemptInterest should be the sum of all Amount in Expenses where TID == TID and TaxCategory == ???.

            TaxableInterest should be the sum of all Amount in Expenses where TID == TID and TaxCategory == ???.

            QualifiedDividends ???

            OrdinaryDividends ???

            IRADistributions ???

            IRATaxable ???

            PensionsAndAnnuities ???

            TaxablePensionsAndAnnuities ???

            SocialSecurityBenefits ???

            TaxableSSB ???

            CapitalGainLoss ???

            CapitalGainLossNotRequired ???

            OtherIncome ???

            TotalIncome is the sum of Wages, TaxableInterest, OrdinaryDividends, IRATaxable, TaxablePensions, TaxableSSB, CapitalGainLoss, and OtherIncome.

            AdjustmentsToIncome ???

            AdjustedGrossIncome is equal to TotalIncome minus AdjustmentsToIncome.

            StandardDeduction ??? //covered in syllabus

            QualifiedBusinessIncomeDeduction ???

            SumStdDeductAndQualifiedBusn is the sum of StandardDeduction and QualifiedBusinessIncomeDeduction.

            TaxableIncome is equal to AdjustedGrossIncome minus SumStdDeductAndQualifiedBusn.

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