#!/bin/python3 

# author: Garrett Wells
# date: 11/11/2020
#
# This file will generate data for SQL data relations based on the schema designed for Back to Brass Tax
from faker import Faker
from sqlalchemy import create_engine, inspect
from collections import defaultdict 
from datetime import datetime

import warnings
import pandas as panda

faker = Faker()

class Possum:


	def __init__(self):

		if input('proceed with default login(user=root, server=localhost)? (y/n): ') == 'y':
			self.database_name = input('enter database name: ')
			self.user = 'root'
			self.password = self.password = input('enter password: ')
			self.server = 'localhost'
		else:
			self.database_name = input('enter database name: ')
			self.user = input('enter username: ' )
			self.password = input('enter password: ')
			self.server = input('enter server: ')
		
		self.printMenu()
		self.parseMenuInput()


	def quit(self):
		#stop program execution
		print('ending execution')
		return;

	def printMenu(self):
		print('Generate CSV for: ')
		print('[1] Taxpayer \n' +
			  '[2] Employers \n' + 
			  '[3] Rental Properties \n' + 
			  '[4] Expenses \n' +
			  '[5] Income Sources \n' + 
			  '[6] Employment Expenses \n' +
			  '[7] Loan Expenses \n' + 
			  '[8] Earnings \n' + 
			  '[9] Employment Earnings \n' + 
			  '[10] Rental Earnings \n' +
			  '[11] Works For \n' +
			  '[12] Owns \n' +
			  '[13] Rents \n' + 
			  '[14] Generate Complete Schema \n')
		return;

	'''
	Given a data frame and table name, create a new table under that name and add it to the database

	dictionary data_dict: the table contents in the form of a default_dict
	string table_name: the string name of the table to add data to 
	'''
	def addDataFrame(self, data_dict, table_name):

		data_framed = panda.DataFrame(data_dict) # convert dict to frame

		print('\nTABLE ' + table_name + '\n' + data_framed.to_string() + '\n')

		engine = create_engine('mysql+mysqlconnector://{user}:{password}@{server}/{database}'.format(user=self.user, password=self.password, server=self.server, database=self.database_name), echo=False)

		# Check for conflicts
		inspector = inspect(engine)
		conflict = False
		for name in inspector.get_table_names():
			#print(table_name + ' == ' + name)
			if name == table_name: 
				conflict = True
				print('WARNING: there is already a table with this name in the schema')
				print('[1] Append data on table\n' +
					  '[2] Replace the table contents\n' + 
					  '[3] Cancel action and quit\n')

				user_opt = input('enter choice: ')
				if user_opt == '1': 
					data_framed.to_sql(table_name, con=engine, index=False, if_exists='append')
				elif user_opt == '2': # overwrite
					data_framed.to_sql(table_name, con=engine, index=False, if_exists='replace')
				else:
					self.quit()

		if conflict == False:
			data_framed.to_sql(table_name, con=engine, index=False)

		with warnings.catch_warnings():
			warnings.simplefilter("ignore", category=UserWarning)
					

	''' 
	Creates data for all these tables and adds them to the database, this should be the best way to generate complete data for testing
	'''
	def addAllTables(self):
		data = self.Taxpayer() 
		tid_keys = self.extract_from_DF(data, 'TID')
		self.addDataFrame(data, 'taxpayer')

		data = self.Employers()
		sid_keys = self.extract_from_DF(data, 'SID')
		self.addDataFrame(data, 'employers')

		data = self.RentalProp()
		rentalID_keys = self.extract_from_DF(data, 'SID')
		self.addDataFrame(data, 'rentalproperties') 

		data = self.Expenses(fk_TID=tid_keys)
		self.addDataFrame(data, 'expenses')

		data = self.IncomeS()
		self.addDataFrame(data, 'incomesources')

		data = self.EmpExpenses()
		self.addDataFrame(data, 'employmentexpenses')

		data = self.LoanExpenses(fk_TID=tid_keys)
		self.addDataFrame(data, 'loanexpenses')

		data = self.Earnings(fk_TID=tid_keys)
		self.addDataFrame(data, 'earnings')

		data = self.EmploymentEarnings(fk_TID=tid_keys, fk_SID=sid_keys)
		self.addDataFrame(data, 'employmentearnings')

		data = self.RentEarnings(fk_taxpayer_TID=tid_keys, fk_rentalprop_SID=rentalID_keys, fk_renter_TID=tid_keys)
		self.addDataFrame(data, 'rentearnings')

		data = self.WorksFor(fk_taxpayer_TID=tid_keys, fk_employers_SID=sid_keys)
		self.addDataFrame(data, 'worksfor')

		data = self.Owns(fk_taxpayer_TID=tid_keys, fk_rentalprop_SID=rentalID_keys)
		self.addDataFrame(data, 'owns')

		data = self.Rents(fk_taxpayer_TID=tid_keys, fk_taxpayer2_TID=tid_keys, fk_rentalprop_SID=rentalID_keys)
		self.addDataFrame(data, 'rents')

	'''
	Take integer input from the user and call a method to generate data for the chosen table
	'''
	def parseMenuInput(self):

		menu_selection = input("Enter Selection To Generate Data(0 - 14): ")

		
		if menu_selection == '0': 
			self.quit()

		elif menu_selection == '1':
			data = self.Taxpayer()
			self.addDataFrame(data, 'taxpayer')

		elif menu_selection == '2': 
			data = self.Employers()
			self.addDataFrame(data, 'employers')

		elif menu_selection == '3': 
			data = self.RentalProp()
			self.addDataFrame(data, 'rentalproperties')

		elif menu_selection == '4': 
			data = self.Expenses()
			self.addDataFrame(data, 'expenses')

		elif menu_selection == '5': 
			data = self.IncomeS()
			self.addDataFrame(data, 'incomesources')

		elif menu_selection == '6': 
			data = self.EmpExpenses()
			self.addDataFrame(data, 'employmentexpenses')

		elif menu_selection == '7':
			data = self.LoanExpenses()
			self.addDataFrame(data, 'loanexpenses')

		elif menu_selection == '8': 
			data = self.Earnings()
			self.addDataFrame(data, 'earnings')

		elif menu_selection == '9': 
			data = self.EmploymentEarnings()
			self.addDataFrame(data, 'employmentearnings')

		elif menu_selection == '10': 
			data = self.RentEarnings()
			self.addDataFrame(data, 'rentearnings')

		elif menu_selection == '11': 
			data = self.WorksFor()
			self.addDataFrame(data, 'worksfor')

		elif menu_selection == '12': 
			data = self.Owns()
			self.addDataFrame(data, 'owns')

		elif menu_selection == '13': 
			data = self.Rents()
			self.addDataFrame(data, 'rents')

		elif menu_selection == '14':
			self.addAllTables()
		return;


	'''
		Get a random tax category from the list of elements in this function
	'''
	def fakeTaxCategory(self):
		return faker.random_element(elements=('StudentLoanPlusInterest', 'HomeMortgageInterest', 'RentalRepair', 'Other')) # TODO: update to final tax categories

	'''
		Get a random year from a list
	'''
	def fakeYearInRange(self, base=None, limit=None):
		if base == limit == None:
			limit = datetime.now().year
			base = limit - 3
		
		years = []

		for i in range(base, limit):
			years.append(i)

		return faker.random_element(elements=tuple(years))


	'''
		Extract data in column from a defaultdict

		returns a data frame
	'''
	def extract_from_DF(self, data_dict, column_name):

		#return data_frame.loc[:, column_name]
		return data_dict[column_name]

	'''
	Generate random data from Faker and put it in a SQL table

	int count: the number of rows to put in the table 
	return: a table of data probably in a string

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
		    ResAptNo VARCHAR(31), #new
		    ResCity VARCHAR(255),
		    ResState VARCHAR(2), #2 letter state code, "DC" counts as a state
			NumDependents INT,
		    #Should ResState include US territories like Puerto Rico (PR)?
		    ResZIP VARCHAR(31), #non-numeric; ZIPs may include hyphens
		    ResSSN NUMERIC (9,0), #new
		    
		    PRIMARY KEY (TID)
		);
	'''
	def Taxpayer(self):
		print('\n---Taxpayer---\n')
		count = int(input("enter number of rows to generate: "))

		fake_data = defaultdict(list)

		for x in range(count):
			# 20 digit numeric
			id = str(x);
			for i in range(20 - len(str(x))):
				id += str(faker.random_digit())

			data_set = ['name', 'sex', 'birthdate']
			line = faker.profile(fields=data_set)

			age = datetime.now().year - line['birthdate'].year

			fake_data["TID"].append(id)
			fake_data["FirstName"].append(faker.first_name())
			fake_data["MiddleInitial"].append(faker.first_name()[0] + ".")
			fake_data["LastName"].append(faker.last_name())
			fake_data["Age"].append(age)
			fake_data["Sex"].append(line['sex'])
			fake_data["DoB"].append(line['birthdate'])
			fake_data["ResAddress"].append(faker.street_address())
			fake_data["ResAptNo"].append(faker.random_int(0, 999)) # Possibility this is also null
			fake_data["ResCity"].append(faker.city())
			fake_data["ResState"].append(faker.state())
			fake_data["NumDependents"].append(faker.random_int(0, 12))
			fake_data["ResZIP"].append(faker.postcode())

			ssn = ''
			for i in range(1, 9):
				ssn += str(faker.random_digit())

			fake_data["ResSSN"].append(ssn)

		print('data placed in dictionary')

		return fake_data

	'''
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
	'''
	def Employers(self):
		print('\n---Employers---\n')
		count = int(input("enter number of rows to generate: "))

		fake_data = defaultdict(list)

		for x in range(count):
			# 20 digit numeric
			id = str(x);
			for i in range(20 - len(str(x))):
				id += str(faker.random_digit())

			data_set = ['company', 'address', 'name']
			line = faker.profile(fields=data_set)

			fake_data["SID"].append(id)
			fake_data["Name"].append(line['company'])
			fake_data['Address'].append(line['address'])
			fake_data["EmployerZIP"].append(faker.postcode())
			fake_data['Classification'].append(self.fakeTaxCategory())
			fake_data['ContactPerson'].append(line['name'])

		print('data placed in dictionary')

		return fake_data

	'''
	Generates random data for the table schema below

		CREATE TABLE RentalProperties
		(
			SID NUMERIC(20, 0) NOT NULL UNIQUE,
			Address VARCHAR(255) NOT NULL,
			ContactPerson VARCHAR(255) NOT NULL,
			
			PRIMARY KEY (SID)
		);

	'''
	def RentalProp(self):
		print('\n---RentalProperties---\n')
		count = int(input("enter number of rows to generate: "))

		fake_data = defaultdict(list)

		for x in range(count):
			# 20 digit numeric
			id = str(x);
			for i in range(20 - len(str(x))):
				id += str(faker.random_digit())

			data_set = ['address', 'name']
			line = faker.profile(fields=data_set)

			fake_data["SID"].append(id)
			fake_data['Address'].append(line['address'])
			fake_data['ContactPerson'].append(line['name'])

		print('data placed in dictionary')

		return fake_data

	'''
	Generates random data for the table schema below

		CREATE TABLE Expenses
		(
			TID NUMERIC(20, 0) NOT NULL,
			EPDate DATE NOT NULL,
			TaxYear YEAR NOT NULL,
			Description VARCHAR(255) NOT NULL,
			TaxCategory VARCHAR(255) NOT NULL,
			Amount INT NOT NULL,
			
			PRIMARY KEY (TID, EPDate, TaxYear, Description, TaxCategory),
			FOREIGN KEY (TID)
			REFERENCES Taxpayer (TID)
			ON DELETE CASCADE ON UPDATE CASCADE
		);

	'''
	def Expenses(self, fk_TID={}):
		print('\n---Expenses---\n')
		count = int(input("enter number of rows to generate: "))

		fake_data = defaultdict(list)

		for x in range(count):
			
			id = str(x); # 20 digit numeric

			for i in range(20 - len(str(x))):

				id += str(faker.random_digit())

			if len(fk_TID) == 0: 
				fake_data["TID"].append(id)
			else: 
				rand_n = faker.random_int(0, len(fk_TID)-1)
				fake_data["TID"].append(fk_TID[rand_n]) #TODO make sure this works

			fake_data["EPDate"].append(faker.date(pattern='%d-%m-%Y'))
			fake_data['TaxYear'].append(faker.year())
			fake_data["Description"].append(faker.sentence())
			fake_data['TaxCategory'].append(self.fakeTaxCategory())
			fake_data['Amount'].append(faker.random_int(1, 1000000000))

		print('data placed in dictionary')

		return fake_data


	'''
	Generate fake data for IncomeSources

		CREATE TABLE IncomeSources
			(
				SID NUMERIC(20, 0) NOT NULL UNIQUE,
				Address VARCHAR(255) NOT NULL,
				ContactPerson VARCHAR(255) NOT NULL,
				
				PRIMARY KEY (SID)
			);
	'''
	def IncomeS(self):
		print('\n---Income Sources---\n')
		count = int(input("enter number of rows to generate: "))

		fake_data = defaultdict(list)

		for x in range(count):
			
			id = str(x); # 20 digit numeric

			for i in range(20 - len(str(x))):

				id += str(faker.random_digit())


			data_set = ['address', 'name']
			line = faker.profile(fields=data_set)

			fake_data["SID"].append(id)
			fake_data['Address'].append(line['address'])
			fake_data['ContactPerson'].append(line['name'])

		print('data placed in dictionary')

		return fake_data

	'''
	Generates test data for the table below

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

	TOOD: this table uses foreign keys... so we need to pull those from another table if possible

	'''
	def EmpExpenses(self, fk_TID={}):
		print('\n---Employee Expenses---\n')
		count = int(input("enter number of rows to generate: "))

		fake_data = defaultdict(list)

		year_limit = datetime.now().year
		year_base = year_limit - 3


		for x in range(count):
			
			id = str(x); # 20 digit numeric

			for i in range(20 - len(str(x))):

				id += str(faker.random_digit())

			if len(fk_TID) == 0:
				fake_data["TID"].append(id)
			else:
				rand_n = faker.random_int(0, len(fk_TID)-1)
				fake_data["TID"].append(fk_TID[rand_n])

			fake_data["ExpDate"].append(faker.date(pattern='%d-%m-%Y'))
			fake_data["TaxYear"].append(self.fakeYearInRange())
			fake_data['Description'].append(faker.sentence())
			fake_data['TaxCategory'].append(self.fakeTaxCategory())
			fake_data['Amount'].append(faker.random_int(1,1000000))
			fake_data['Purpose'].append(faker.random_element(elements=('no reason', 'hungry'))) # TODO: What is Purpose in the EmploymentExpenses?
			fake_data['EmployerAuthorized'].append(faker.random_element(elements=('yes', 'no')))

		print('data placed in dictionary')

		return fake_data


	'''
	Generate data for the table below

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
	'''
	def LoanExpenses(self, fk_TID={}):
		print('\n---Loan Expenses---\n')
		count = int(input("enter number of rows to generate: "))

		fake_data = defaultdict(list)

		for x in range(count):
			
			id = str(x); # 20 digit numeric
			lender_id = '' # 20 digit numeric

			for i in range(20 - len(str(x))):

				if i < (20 - len(str(x))): # account for unique number at beginning of string
					id += str(faker.random_digit())

				lender_id += str(faker.random_digit())


			loan_num = '' # 25 digit numeric identifier
			for i in range(25):

				loan_num += str(faker.random_digit())


			if len(fk_TID) == 0:
				fake_data["TID"].append(id)
			else:
				rand_n = faker.random_int(0, len(fk_TID)-1)
				fake_data["TID"].append(fk_TID[rand_n])

			fake_data["LPDate"].append(faker.date(pattern='%d-%m-%Y'))
			fake_data["TaxYear"].append(self.fakeYearInRange())
			fake_data['Description'].append(faker.sentence())
			fake_data['TaxCategory'].append(self.fakeTaxCategory())
			fake_data['Amount'].append(faker.random_int(1,1000000))
			fake_data['LenderID'].append(lender_id)
			fake_data['LoanNumber'].append(loan_num)
			fake_data['LoanAmount'].append(faker.random_int(1, 1000000)) 
			fake_data['RemainingBalance'].append(faker.random_int(0, 1000000)) 

		print('data placed in dictionary')

		return fake_data		

	'''
	Generate data for the table below

	CREATE TABLE Earnings
		(
			TID NUMERIC(20, 0) NOT NULL,
			EarnDate DATE NOT NULL,
			TaxYear YEAR NOT NULL,
			Amount INT,
			Withheld INT, #new
			
			PRIMARY KEY (TID, EarnDate, TaxYear),
			FOREIGN KEY (TID)
			REFERENCES Taxpayer(TID)
			ON DELETE CASCADE ON UPDATE CASCADE
		);
	'''
	def Earnings(self, fk_TID={}):
		print('\n---Earnings---\n')
		count = int(input("enter number of rows to generate: "))

		fake_data = defaultdict(list)

		for x in range(count):
			
			id = str(x); # 20 digit numeric

			for i in range(20 - len(str(x))):

				id += str(faker.random_digit())

			if len(fk_TID) == 0:
				fake_data["TID"].append(id)
			else:
				rand_n = faker.random_int(0, len(fk_TID)-1)
				fake_data["TID"].append(fk_TID[rand_n])

			fake_data["EarnDate"].append(faker.date(pattern='%d-%m-%Y'))
			fake_data["TaxYear"].append(self.fakeYearInRange())
			earned = faker.random_int(1,1000000)
			fake_data['Amount'].append(earned)
			fake_data['Withheld'].append(0.10*earned) # withheld may be more than 10% of total
			
		print('data placed in dictionary')

		return fake_data		


	'''
	Generate the data for the table below

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
	'''
	def EmploymentEarnings(self, fk_TID={}, fk_SID={}):
		print('\n---Employment Earnings---\n')
		count = int(input("enter number of rows to generate: "))

		fake_data = defaultdict(list)

		for x in range(count):
			
			id = str(x); # 20 digit numeric

			for i in range(20 - len(str(x))):
				id += str(faker.random_digit())

			if len(fk_TID) == 0:
				fake_data["TID"].append(id) 
			else:
				rand_n = faker.random_int(0, len(fk_TID)-1)
				fake_data["TID"].append(fk_TID[rand_n]) 

			fake_data["EarnDate"].append(faker.date(pattern='%d-%m-%Y'))
			fake_data["TaxYear"].append(self.fakeYearInRange())
			fake_data['Amount'].append(faker.random_int(1,1000000))
			fake_data['TaxWithheld'].append(faker.random_int(0, 10)) # TODO: 'TaxWithheld' should be some percentage

			if len(fk_SID) == 0:
				fake_data['EmployerID'].append(id)
			else:
				rand_n = faker.random_int(0, len(fk_SID)-1)
				fake_data["EmployerID"].append(fk_SID[rand_n]) 

			fake_data['RetirementContributions'].append(faker.random_int(0, 10000))
			
		print('data placed in dictionary')

		return fake_data		


	'''
	Generate data for the table below

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
	'''
	def RentEarnings(self, fk_taxpayer_TID={}, fk_rentalprop_SID={}, fk_renter_TID={}):
		print('\n---Rent Earnings---\n')
		count = int(input("enter number of rows to generate: "))

		fake_data = defaultdict(list)

		for x in range(count):
			
			id = str(x); # 20 digit numeric

			for i in range(20 - len(str(x))):

				id += str(faker.random_digit())

			if len(fk_taxpayer_TID) == 0: # todo put foreign keys in the addAllTables
				fake_data["TID"].append(id) 
			else:
				rand_n = faker.random_int(0, len(fk_taxpayer_TID)-1)
				fake_data["TID"].append(fk_taxpayer_TID[rand_n]) 

			fake_data["EarnDate"].append(faker.date(pattern='%d-%m-%Y'))
			fake_data["TaxYear"].append(self.fakeYearInRange())

			if len(fk_rentalprop_SID) == 0:
				fake_data['HomeID'].append(id) 
			else:
				rand_n = faker.random_int(0, len(fk_rentalprop_SID)-1)
				fake_data["HomeID"].append(fk_rentalprop_SID[rand_n]) 

			if len(fk_renter_TID) == 0:
				fake_data['RenterID'].append(id) 
			else:
				rand_n = faker.random_int(0, len(fk_renter_TID)-1)
				fake_data["RenterID"].append(fk_renter_TID[rand_n]) 

			fake_data['Amount'].append(faker.random_int(1,1000000))
			
		print('data placed in dictionary')

		return fake_data		


	'''
	Generate data for the table below

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
	'''
	def WorksFor(self, fk_taxpayer_TID={}, fk_employers_SID={}):
		print('\n---Works For---\n')
		count = int(input("enter number of rows to generate: "))

		fake_data = defaultdict(list)

		for x in range(count):
			
			id = str(x); # 20 digit numeric

			for i in range(20 - len(str(x))):

				id += str(faker.random_digit())

			if len(fk_taxpayer_TID) == 0: # update Worksfor in addAllTables
				fake_data["EmployerID"].append(id) 
			else:
				rand_n = faker.random_int(0, len(fk_employers_SID)-1)
				fake_data["EmployerID"].append(fk_employers_SID[rand_n]) 

			if len(fk_taxpayer_TID) == 0:
				fake_data["TID"].append(id) 
			else:
				rand_n = faker.random_int(0, len(fk_taxpayer_TID)-1)
				fake_data["TID"].append(fk_taxpayer_TID[rand_n]) 

			fake_data['AnnualSalary'].append(faker.random_int(1,1000000))
			fake_data["DateJoined"].append(faker.date(pattern='%d-%m-%Y'))
			
		print('data placed in dictionary')

		return fake_data


	'''
	Generate data for the table below

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
	'''
	def Owns(self, fk_taxpayer_TID={}, fk_rentalprop_SID={}):
		print('\n---Owns---\n')
		count = int(input("enter number of rows to generate: "))

		fake_data = defaultdict(list)

		for x in range(count):
			
			id = str(x); # 20 digit numeric

			for i in range(20 - len(str(x))):

				id += str(faker.random_digit())


			if len(fk_taxpayer_TID) == 0: 
				fake_data["OID"].append(id) 
			else:
				rand_n = faker.random_int(0, len(fk_taxpayer_TID)-1)
				fake_data["OID"].append(fk_taxpayer_TID[rand_n]) 

			if len(fk_rentalprop_SID) == 0:
				fake_data["HomeID"].append(id) 
			else:
				rand_n = faker.random_int(0, len(fk_rentalprop_SID)-1)
				fake_data["TID"].append(fk_rentalprop_SID[rand_n]) 
			
		print('data placed in dictionary')

		return fake_data


	'''
	Generate data for table below

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
	'''
	def Rents(self, fk_taxpayer_TID={}, fk_taxpayer2_TID={}, fk_rentalprop_SID={}):
		print('\n---Rents---\n')
		count = int(input("enter number of rows to generate: "))

		fake_data = defaultdict(list)

		for x in range(count):
			
			id = str(x); # 20 digit numeric

			for i in range(20 - len(str(x))):

				id += str(faker.random_digit())

			if len(fk_taxpayer_TID) == 0: 
				fake_data["RenterID"].append(id) 
			else:
				rand_n = faker.random_int(0, len(fk_taxpayer_TID)-1)
				fake_data["RenterID"].append(fk_taxpayer_TID[rand_n]) 

			if len(fk_taxpayer2_TID) == 0:
				fake_data["OwnerID"].append(id) 
			else: 
				rand_n = faker.random_int(0, len(fk_taxpayer2_TID)-1)
				fake_data["OwnerID"].append(fk_taxpayer2_TID[rand_n]) 

			if len(fk_rentalprop_SID) == 0:
				fake_data['PropertyID'].append(id) 
			else:
				rand_n = faker.random_int(0, len(fk_rentalprop_SID)-1)
				fake_data["PropertyID"].append(fk_rentalprop_SID[rand_n]) 

			fake_data['RentPerMonth'].append(faker.random_int(1, 1000000))
			fake_data['StartDate'].append(faker.date(pattern='%d-%m-%Y'))
			
		print('data placed in dictionary')

		return fake_data

print('\n' +
	  '||  ########   #######   ######   ######  ##     ## ##     ##\n' + 
	  '||  ##     ## ##     ## ##    ## ##    ## ##     ## ###   ###\n' +
      '||  ##     ## ##     ## ##       ##       ##     ## #### ####\n' +
      '||  ########  ##     ##  ######   ######  ##     ## ## ### ##\n' +
      '||  ##        ##     ##       ##       ## ##     ## ##     ##\n' +
      '||  ##        ##     ## ##    ## ##    ## ##     ## ##     ##\n' +
      '||  ##         #######   ######   ######   #######  ##     ##\n' +
      '+=============================================================\n' + 
      ' A fake data generator for CS360 Semester Project: Brass Tax.\n\n\n')

start = Possum()
