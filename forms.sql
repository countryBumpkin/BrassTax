CREATE TABLE TaxReturn
(
	TID NUMERIC(20, 0) NOT NULL,
	FilingStatus VARCHAR(255),
	FirstName VARCHAR(255),
	MiddleInitial VARCHAR(1),
	LastName VARCHAR(255),
	SSN NUMERIC(9, 0), #do not hyphenate
	SpouseFirst VARCHAR(255),
	SpouseMiddle VARCHAR(1),
	SpouseLast VARCHAR(255),
	SpouseSSN NUMERIC(9, 0),
	ResAddress VARCHAR(255),
	ResAptNo VARCHAR(31),
	ResCity VARCHAR(255),
	ResState VARCHAR(255),
	ResZip VARCHAR(31),
	ResFCountry VARCHAR(255),
	ResFProvince VARCHAR(255), #or state, or county
	ResFPostalCode VARCHAR(31),
	AmDependent VARCHAR(3), #yes or no
	SpouseDependent VARCHAR(3),
	SpouseItemizesOrDualStatus VARCHAR(3),
	BornBefore1955 VARCHAR(3), #Jan 2nd
	AmBlind VARCHAR(3),
	SpouseBornBefore1955 VARCHAR(3),
	SpouseBlind VARCHAR(3),
	Wages INT,
	TaxExemptInterest INT,
	TaxableInterest INT,
	QualifiedDividends INT,
	OrdinaryDividends INT,
	IRADistributions INT,
	IRATaxable INT,
	PensionsAndAnnuities INT,
	TaxablePensionsAndAnnuities INT,
	SocialSecurityBenefits INT,
	TaxableSSB INT,
	CapitalGainLoss INT,
	CapitalGainLossNotRequired VARCHAR(3),
	OtherIncome INT,
	TotalIncome INT, #Sum of Wages, TaxableInterest, OrdinaryDividends, IRATaxable, TaxablePensions, TaxableSSB, CapitalGainLoss, and OtherIncome.
	AdjustmentsToIncome INT,
	AdjustedGrossIncome INT, #Subtract AdjustmentsToIncome from TotalIncome
	StandardDeduction INT,
	QualifiedBusinessIncomeDeduction INT,
	SumStdDeductAndQualifiedBusn INT,
	TaxableIncome INT, #Subtract SumStdDeduct from AdjustedGrossIncome

	PRIMARY KEY(),
	FOREIGN KEY ()
	REFERENCES
	ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Dependent #For TaxReturn
(
	TID NUMERIC(20, 0) NOT NULL, #TID of person who can claim this dependent
	DepFirst VARCHAR(255),
	DepLast VARCHAR(255),
	SSN NUMERIC(9, 0), #NO HYPHENS
	Relationship VARCHAR(255),
	ChildTaxCredit VARCHAR(3), #yes or no 
	CreditForOtherDependents VARCHAR(3),
	
	PRIMARY KEY (),
	FOREIGN KEY ()
	REFERENCES 
	ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE W2
(
	TID NUMERIC(20, 0) NOT NULL,
	SID NUMERIC(20, 0) NOT NULL,
	EName VARCHAR(255),
	EAddress VARCHAR(255),
	EZIP VARCHAR(31),
	TFirst VARCHAR(255),
	TMiddle VARCHAR(1),
	TLast VARCHAR(255),
	TAddress VARCHAR(255),
	TCity VARCHAR(255),
	TState VARCHAR(2),
	TZIP VARCHAR(31)
	TSSN NUMERIC(9,0),
	
	#gotta grab and store address info here for posterity (if a Taxpayer updates their address, it shouldnt change here)
	WagesTipsEtc INT,
	FedIncTax INT, #withheld
	SSWages INT, #Social Security
	SSTax INT,
	MedicareWages INT,
	MedicareTax INT,
	SSTips INT,
	AllocatedTips INT,
	DependentCareBenefits INT,
	StateWagesTipsEtc INT,
	StateIncomeTax INT,
	LocalWagesTipsEtc INT,
	LocalIncomeTax INT,
	LocalityName VARCHAR(255),
	
	PRIMARY KEY (),
	FOREIGN KEY ()
	REFERENCES ()
	ON DELETE CASCADE ON UPDATE CASCADE,
);