CREATE TABLE Taxpayer
(
	TID NUMERIC(20, 0) NOT NULL UNIQUE,
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
    
    PRIMARY KEY (TID)
);

/*
CREATE TABLE Expenses #Relevant, deductible expense types are subtracted from Income to get Adjusted Taxable Income.
#Individuals may report non-deductible expenses, but those are ignored (I think).
#Individual transactions are NOT to be reported here. Only the yearly sums, per expense type.
(
	TID NUMERIC(20, 0) NOT NULL,
    ExpenseType VARCHAR(255) NOT NULL, #"Student Loan", "Home Mortgage", "Rental Repair", and "Job Related" are the relevant expenses. Use "Other" for any other expenses.
    TotalYearlyExpense INT NOT NULL,
    NonInterestExpense INT NOT NULL,
    InterestExpense INT NOT NULL, #For home mortgages, only interests are deductible. Student loans are entirely deductible. Other expenses shouldnt have interests.
    #NonInterestExpense + InterestExpense should equal TotalYearlyExpense.
    
    PRIMARY KEY (TID, ExpenseType), #you must UPDATE, or DELETE a row to redo its Expenses.
    FOREIGN KEY (TID)
    REFERENCES Taxpayer (TID)
    ON DELETE CASCADE ON UPDATE CASCADE
);
*/

CREATE TABLE Expenses
(
	TID NUMERIC(20, 0) NOT NULL,
	EPDate DATE NOT NULL,
	TaxYear YEAR NOT NULL,
	Description VARCHAR(255) NOT NULL,
	TaxCategory VARCHAR(255) NOT NULL,
	Amount INT NOT NULL,
	
	PRIMARY KEY (TID, ExpDate, TaxYear, Description, TaxCategory),
	FOREIGN KEY (TID)
	REFERENCES Taxpayer (TID)
	ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE EmploymentExpenses
(
	TID NUMERIC(20, 0) NOT NULL,
	ExPDate DATE NOT NULL,
	TaxYear YEAR NOT NULL,
	Description VARCHAR(255) NOT NULL,
	TaxCategory VARCHAR(255) NOT NULL,
	Amount INT NOT NULL,
	Purpose VARCHAR(255),
	EmployerAuthorized VARCHAR(4), #Yes/No
	
	PRIMARY KEY (TID, ExpDate, TaxYear, Description, TaxCategory),
	FOREIGN KEY (TID)
	REFERENCES Taxpayer (TID)
	ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE LoanExpenses
(
	TID NUMERIC(20, 0) NOT NULL,
	LPDate DATE NOT NULL,
	TaxYear YEAR NOT NULL,
	Description VARCHAR(255) NOT NULL,
	TaxCategory VARCHAR(255) NOT NULL,
	Amount INT NOT NULL,
	LenderID NUMERIC(20, 0) NOT NULL,
	LoanNumber NUMERIC(25, 0) NOT NULL,
	LoanAmount INT,
	RemainingBalance INT,
	
	PRIMARY KEY (TID, ExpDate, TaxYear, Description, TaxCategory),
	FOREIGN KEY (TID)
	REFERENCES Taxpayer (TID)
	ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Earnings
(
	TID NUMERIC(20, 0) NOT NULL,
	EarnDate DATE NOT NULL,
	TaxYear YEAR NOT NULL,
	Amount INT,
	
	PRIMARY KEY (TID, EarnDate, TaxYear),
	FOREIGN KEY (TID)
	REFERENCES Taxpayer(TID)
	ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE EmploymentEarnings
(
	TID NUMERIC(20, 0) NOT NULL,
	EarnDate DATE NOT NULL,
	TaxYear YEAR NOT NULL,
	Amount INT,
	TaxWithheld INT,
	EmployerID NUMERIC(20, 0) NOT NULL,
	RetirementContributions INT,
	
	PRIMARY KEY (TID, EarnDate, TaxYear, EmployerID),
	FOREIGN KEY (TID)
	REFERENCES Taxpayer(TID)
	ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (EmployerID)
	REFERENCES Employers (SID)
	ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE RentalEarnings
(
	TID NUMERIC(20, 0) NOT NULL,
	EarnDate DATE NOT NULL,
	TaxYear YEAR NOT NULL,
	HomeID NUMERIC(20, 0) NOT NULL,
	RenterID NUMERIC(20, 0) NOT NULL,
	Amount INT,
	
	PRIMARY KEY (TID, EarnDate, TaxYear, HomeID, RenterID),
	FOREIGN KEY (TID)
	REFERENCES Taxpayer(TID)
	ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (HomeID)
	REFERENCES RentalProperties (SID)
	ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (RenterID)
	REFERENCES Taxpayer(TID)
	ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IncomeSources
(
	SID NUMERIC(20, 0) NOT NULL UNIQUE,
	Address VARCHAR(255) NOT NULL,
	ContactPerson VARCHAR(255) NOT NULL,
	
	PRIMARY KEY (SID)
);

CREATE TABLE Employers
(
	SID NUMERIC(20, 0) NOT NULL UNIQUE,
	Name VARCHAR(255),
	Address VARCHAR(255) NOT NULL,
	Classification VARCHAR(255),
	ContactPerson VARCHAR(255) NOT NULL,
	
	PRIMARY KEY (SID)
);

CREATE TABLE RentalProperties
(
	SID NUMERIC(20, 0) NOT NULL UNIQUE,
	Address VARCHAR(255) NOT NULL,
	ContactPerson VARCHAR(255) NOT NULL,
	
	PRIMARY KEY (SID)
);

CREATE TABLE WorksFor
(
	EmployerID NUMERIC(20, 0) NOT NULL,
	TID NUMERIC(20, 0) NOT NULL,
	AnnualSalary INT,
	DateJoined Date,
	
	PRIMARY KEY (EmployerID, TID),
	FOREIGN KEY (TID)
	REFERENCES Taxpayer(TID)
	ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (EmployerID)
	REFERENCES Employers (SID)
	ON DELETE CASCADE ON UPDATE CASCADE	
);

CREATE TABLE Owns
(
	OID NUMERIC(20, 0) NOT NULL,
	HomeID NUMERIC(20, 0) NOT NULL,
	
	PRIMARY KEY (OID, HomeID),
	FOREIGN KEY (OID)
	REFERENCES Taxpayer(TID)
	ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (HomeID)
	REFERENCES RentalProperties(SID)
	ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Rents
(
	RenterID NUMERIC(20, 0) NOT NULL,
	OwnerID NUMERIC(20, 0) NOT NULL,
	PropertyID NUMERIC(20, 0) NOT NULL,
	RentPerMonth INT,
	StartDate DATE,
	
	PRIMARY KEY (RenterID, OwnerID, PropertyID),
	FOREIGN KEY (RenterID)
	REFERENCES Taxpayer(TID)
	ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (OwnerID)
	REFERENCES TaxPayer(TID)
	ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (PropertyID)
	REFERENCES RentalProperties(SID)
	ON DELETE CASCADE ON UPDATE CASCADE
);

























































