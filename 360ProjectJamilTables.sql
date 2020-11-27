#Atomic Tables

CREATE TABLE Taxpayer
(
	TID NUMERIC(20, 0) NOT NULL UNIQUE,
    FirstName VARCHAR(255),
	MiddleInitial VARCHAR(1),
	LastName VARCHAR(255),
    Age INT, #65+ year-old taxpayers get a $500 deduction
    Sex VARCHAR(255), #Female taxpayers get a $500 deduction
    DoB DATE,
    ResAddress VARCHAR(255),
	ResAptNo VARCHAR(31),
    ResCity VARCHAR(255),
    ResState VARCHAR(2), #2 letter state code, "DC" counts as a state
	NumDependents INT,
    #Should ResState include US territories like Puerto Rico (PR)?
    ResZIP VARCHAR(31), #non-numeric; ZIPs may include hyphens
	ResSSN NUMERIC (9,0),
    
    PRIMARY KEY (TID)
);

CREATE TABLE Employers
(
	SID NUMERIC(20, 0) NOT NULL UNIQUE,
	Name VARCHAR(255),
	Address VARCHAR(255) NOT NULL,
	EmployerZIP VARCHAR(31),
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

CREATE TABLE Expenses
(
	TID NUMERIC(20, 0) NOT NULL,
	EPDate DATE NOT NULL,
	TaxYear YEAR NOT NULL,
	Description VARCHAR(255) NOT NULL,
	TaxCategory VARCHAR(255) NOT NULL, #StudentLoanPlusInterest, HomeMortgageInterest, RentalRepair, or Other
	Amount INT NOT NULL,
	
	PRIMARY KEY (TID, EPDate, TaxYear, Description, TaxCategory),
	FOREIGN KEY (TID)
	REFERENCES Taxpayer (TID)
	ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IncomeSources
(
	SID NUMERIC(20, 0) NOT NULL UNIQUE,
	Address VARCHAR(255) NOT NULL,
	ContactPerson VARCHAR(255) NOT NULL,
	
	PRIMARY KEY (SID)
);

#Foreign key-ers

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
	
	PRIMARY KEY (TID, LPDate, TaxYear, Description, TaxCategory),
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
	TaxWithheld INT, #new (renamed)
	
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
	TaxWithheld INT, #new?
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
	TaxWithheld INT, #new?
	
	
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























































