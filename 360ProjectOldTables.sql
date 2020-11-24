/*
#############################################
CS 360 Project CREATE TABLE Statements 

Greyson Biggs
Garrett Wells
Gabriel Hasenoehrl

CS 360 - Database Systems
#############################################
*/

###############
#1. Taxpayer Info.
###############

CREATE TABLE Individual
(
	IID NUMERIC(20, 0) NOT NULL UNIQUE,
    Name VARCHAR(255) NOT NULL,
    Age INT, #65+ year-old taxpayers get a $500 deduction
    Sex VARCHAR(255), #Female taxpayers get a $500 deduction
    DoB DATE,
    ResAddress VARCHAR(255),
    ResCity VARCHAR(255),
    ResState VARCHAR(2), #2 letter state code, "DC" counts as a state
	NumDependents INT,
    #Should ResState include US territories like Puerto Rico (PR)?
    #ResZIP VARCHAR(255), #non-numeric; ZIPs may include hyphens
    
    PRIMARY KEY (IID)
);

###############
#4. Deductible Expenses
###############

CREATE TABLE Expenses #Relevant, deductible expense types are subtracted from Income to get Adjusted Taxable Income.
#Individuals may report non-deductible expenses, but those are ignored (I think).
#Individual transactions are NOT to be reported here. Only the yearly sums, per expense type.
(
	IID NUMERIC(20, 0) NOT NULL,
    ExpenseType VARCHAR(255) NOT NULL, #"Student Loan", "Home Mortgage", "Rental Repair", and "Job Related" are the relevant expenses. Use "Other" for any other expenses.
    TotalYearlyExpense INT NOT NULL,
    NonInterestExpense INT NOT NULL,
    InterestExpense INT NOT NULL, #For home mortgages, only interests are deductible. Student loans are entirely deductible. Other expenses shouldnt have interests.
    #NonInterestExpense + InterestExpense should equal TotalYearlyExpense.
    
    PRIMARY KEY (IID, ExpenseType), #you must UPDATE, or DELETE a row to redo its Expenses.
    FOREIGN KEY (IID)
    REFERENCES Individual (IID)
    ON DELETE CASCADE ON UPDATE CASCADE
);

###############
#2. Gross Taxable Incomes.
###############

#All income


CREATE TABLE Wages #W-2, reported by businesses (SID)
(
	IID NUMERIC (20, 0) NOT NULL,
	
);

#CREATE TABLE InvestmentInterests #I-2, reported by banks/brokers (SID)

#CREATE TABLE RentalIncome #R-2, reported by renters (IID)

#CREATE TABLE FarmIncome #F-2, self-reported (IID)

#CREATE TABLE BusinessIncome #B-2, reported by business (SID)

#CREATE TABLE CapitalGains #C-2, reported by banks (SID)

#CREATE TABLE OtherIncome #O-2, reported by agencies (SID)

###############
#3. Deductions.
###############

CREATE TABLE IndividualDisabilities #Handicapped taxpayers get a $750 tax deduction
(
	IID NUMERIC(20,0) NOT NULL,
	Disability VARCHAR(255) NOT NULL,
    
    PRIMARY KEY (IID, Disability),
    FOREIGN KEY (PID)
    REFERENCES Individual (PID)
    ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (Disability)
	REFERENCES Disabilities (Disability)
    ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Disabilities
(
	Disability VARCHAR(255) NOT NULL UNIQUE,
    PRIMARY KEY (Disability)
);

CREATE TABLE IsWoundedArmedForces #Wounded Armed Forces Taxpayers get a $2000 tax deduction
#Still not sure if this needs to be its own table...
(
	IID Numeric(20, 0) NOT NULL UNIQUE,
    
    PRIMARY KEY (IID),
    FOREIGN KEY (IID)
    REFERENCES Individual (IID)
    ON DELETE CASCADE ON UPDATE CASCADE
);

###############
#7. Things an individual is required to report.
###############

CREATE TABLE IndividualHome #Individuals may own/rent multiple homes.
(
	IID NUMERIC(20, 0) NOT NULL,
    Owns VARCHAR(10) NOT NULL, #either "owns" or "rented"
    HomeState VARCHAR(2) NOT NULL, #2 letter state code
    HomeCity VARCHAR(255) NOT NULL,
    HomeAddress VARCHAR(255) NOT NULL,
    
    PRIMARY KEY (IID, HomeState, HomeCity, HomeAddress),
    FOREIGN KEY (IID)
    REFERENCES Individual (IID)
    ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IndividualFarm #Individuals may own multiple farms.
(
	IID NUMERIC(20, 0) NOT NULL,
    FarmState VARCHAR(2) NOT NULL, #2 letter state code
    FarmCity VARCHAR(255) NOT NULL,
    FarmAddress VARCHAR(255) NOT NULL,
    
    PRIMARY KEY (IID, FarmState, FarmCity, FarmAddress),
    FOREIGN KEY (IID)
    REFERENCES Individual (IID)
    ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Business
(
	SID NUMERIC(20, 0) NOT NULL UNIQUE,
	BusinessName VARCHAR(255) NOT NULL,
    BusinessState VARCHAR(2) NOT NULL,
    BusinessCity VARCHAR(255) NOT NULL,
    BusinessAddress VARCHAR(255) NOT NULL,
    
    PRIMARY KEY (SID)
);

CREATE TABLE IndividualOwnedBusiness #Individuals may own multiple businesses.
(
	IID NUMERIC(20, 0) NOT NULL,
    SID NUMERIC(20, 0) NOT NULL,
    
    PRIMARY KEY (IID, SID),
    FOREIGN KEY (IID)
    REFERENCES Individual (IID)
    ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (SID)
    REFERENCES Business (SID)
    ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IndividualEmployer
(
	IID NUMERIC(20, 0) NOT NULL,
    SID NUMERIC(20, 0) NOT NULL,
    
    PRIMARY KEY (IID, SID),
    FOREIGN KEY (IID)
    REFERENCES Individual (IID)
    ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (SID)
    REFERENCES Business (SID)
    ON DELETE CASCADE ON UPDATE CASCADE
);

###############
#6. Cities which require a minimum annual city tax (in case a taxpayer has no tax liability)
###############

CREATE TABLE MinAnnualTaxCities
(
	MinAnnualTaxCity VARCHAR(255) NOT NULL UNIQUE,
    MinAnnualTax INT NOT NULL,
    
	PRIMARY KEY (MinAnnualTaxCity)
);

###############
#5. Adjusted Taxable Income Scale
###############

/*
CREATE TABLE AdjustedTaxableIncomeScale
(
	#Need to figure out how to handle this table, or if this should be a table at all...
	Stuff INT NOT NULL,
    PRIMARY KEY (Stuff)
);
*/



