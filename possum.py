#!/bin/python3 

# author: Garrett Wells
# date: 11/11/2020
#
# This file will generate data for SQL data relations based on the schema designed for Back to Brass Tax
from faker import Faker
from sqlalchemy import create_engine
from collections import defaultdict 
from datetime import datetime
import warnings
import pandas as panda

faker = Faker()

class Possum:


	def __init__(self, database_name):
		self.database_name = database_name
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

		print(data_framed.to_string())

		engine = create_engine('mysql+mysqlconnector://{user}:{password}@{server}/{database}'.format(user='root', password='GW091799', server='localhost', database=self.database_name), echo=False)

		data_framed.to_sql(table_name, con=engine, index=False)

		with warnings.catch_warnings():
			warnings.simplefilter("ignore", category=UserWarning)


	''' 
	Creates data for all these tables and adds them to the database, this should be the best way to generate complete data for testing

	TODO: implement code to handle foreign keys here
	'''
	def addAllTables(self):



		data = self.Taxpayer() 
		self.addDataFrame(data, 'TaxPayer')

		data = self.Employers()
		self.addDataFrame(data, 'Employers')

		data = self.RentalProp()
		self.addDataFrame(data, 'RentalProperties') 

		data = self.Expenses()
		self.addDataFrame(data, 'Expenses')

		data = self.IncomeS()
		self.addDataFrame(data, 'IncomeSources')

		data = self.EmpExpenses()
		self.addDataFrame(data, 'EmploymentExpenses')

		data = self.LoanExpenses()
		self.addDataFrame(data, 'LoanExpenses')

		data = self.Earnings()
		self.addDataFrame(data, 'Earnings')

		data = self.EmploymentEarnings()
		self.addDataFrame(data, 'EmploymentEarnings')

		data = self.RentEarnings()
		self.addDataFrame(data, 'RentEarnings')

		data = self.WorksFor()
		self.addDataFrame(data, 'WorksFor')

		data = self.Owns()
		self.addDataFrame(data, 'Owns')

		data = self.Rents()
		self.addDataFrame(data, 'Rents')

	'''
	Take integer input from the user and call a method to generate data for the chosen table
	'''
	def parseMenuInput(self):

		menu_selection = input("Enter Selection To Generate Data(0 - 13): ")

		
		if menu_selection == '0': 
			self.quit()

		elif menu_selection == '1':
			data = self.Taxpayer()
			self.addDataFrame(data, 'Taxpayer')

		elif menu_selection == '2': 
			data = self.Employers()
			self.addDataFrame(data)

		elif menu_selection == '3': 
			data = self.RentalProp()
			self.addDataFrame(data)

		elif menu_selection == '4': 
			data = self.Expenses()
			self.addDataFrame(data)

		elif menu_selection == '5': 
			data = self.IncomeS()
			self.addDataFrame(data)

		elif menu_selection == '6': 
			data = self.EmpExpenses()
			self.addDataFrame(data)

		elif menu_selection == '7':
			data = self.LoanExpenses()
			self.addDataFrame(data)

		elif menu_selection == '8': 
			data = self.Earnings()

		elif menu_selection == '9': 
			data = self.EmploymentEarnings()

		elif menu_selection == '10': 
			data = self.RentEarnings()

		elif menu_selection == '11': 
			data = self.WorksFor()

		elif menu_selection == '12': 
			data = self.Owns()

		elif menu_selection == '13': 
			data = self.Rents()

		elif menu_selection == '14':
			self.addAllTables()
		return;

	'''
	Generate random data from Faker and put it in a SQL table

	int count: the number of rows to put in the table 
	return: a table of data probably in a string
	'''
	def Taxpayer(self):
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
			fake_data["ResCity"].append(faker.city())
			fake_data["ResState"].append(faker.state())
			fake_data["NumDependents"].append(faker.random_int(0, 12))
			fake_data["ResZIP"].append(faker.postcode())

		print('data placed in dictionary')

		return fake_data

	def Employers(self):
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
			# TODO: what should Classification be?
			fake_data['Classification'].append(faker.random_element(elements=('high', 'mid', 'low')))
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

		TODO: test RentalProp Function

	'''
	def RentalProp(self):

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

		TODO: test this function

	'''
	def Expenses(self):

		count = int(input("enter number of rows to generate: "))

		fake_data = defaultdict(list)

		for x in range(count):
			
			id = str(x); # 20 digit numeric

			for i in range(20 - len(str(x))):

				id += str(faker.random_digit())


			data_set = ['company', 'address', 'name']
			line = faker.profile(fields=data_set)

			fake_data["TID"].append(id)
			fake_data["EPDate"].append(faker.date(pattern='%d-%m-%Y'))
			fake_data['TaxYear'].append(faker.year())
			fake_data["Description"].append(faker.sentence())
			fake_data['TaxCategory'].append(faker.random_element(elements=('high', 'mid', 'low'))) # TODO: what should Classification be?
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

		TODO: finish and test this function

	'''
	def IncomeS(self):

		count = int(input("enter number of rows to generate: "))

		fake_data = defaultdict(list)

		for x in range(count):
			
			id = str(x); # 20 digit numeric

			for i in range(20 - len(str(x))):

				id += str(faker.random_digit())


			data_set = ['address', 'name']
			line = faker.profile(fields=data_set)

			fake_data["SID"].append(id)
			fake_data['Address'].append(line['address']) # TODO: what should Classification be?
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
	def EmpExpenses(self):

		count = int(input("enter number of rows to generate: "))

		fake_data = defaultdict(list)

		for x in range(count):
			
			id = str(x); # 20 digit numeric

			for i in range(20 - len(str(x))):

				id += str(faker.random_digit())


			data_set = ['address', 'name']
			line = faker.profile(fields=data_set)

			fake_data["TID"].append(id)
			fake_data["ExpDate"].append(faker.date(pattern='%d-%m-%Y'))
			fake_data["TaxYear"].append(faker.year()) # TODO: this maybe should be the current year...
			fake_data['Description'].append(faker.sentence())
			fake_data['TaxCategory'].append(faker.random_element(elements=('high', 'mid', 'low'))) # TODO: decide what TaxCategory is
			fake_data['Amount'].append(faker.random_int(1,1000000))
			fake_data['Purpose'].append(faker.random_element(elements=('no reason', 'hungry'))) # TODO: What is Purpose in the EmploymentExpenses?
			fake_data['EmployerAuthorized'].append(faker.random_element(elements=('yes', 'no'))) # TODO: make this yes/no

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

		TODO: finish and test LoanExpenses()

	'''
	def LoanExpenses(self):

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


			data_set = ['address', 'name']
			line = faker.profile(fields=data_set)

			fake_data["TID"].append(id) # TODO: TID needs to be a foreign key/derived value
			fake_data["LPDate"].append(faker.date(pattern='%d-%m-%Y'))
			fake_data["TaxYear"].append(faker.year()) # TODO: this maybe should be the current year...
			fake_data['Description'].append(faker.sentence())
			fake_data['TaxCategory'].append(faker.random_element(elements=('high', 'mid', 'low'))) # TODO: decide what TaxCategory is
			fake_data['Amount'].append(faker.random_int(1,1000000))
			fake_data['LenderID'].append(lender_id) # TODO: Generate a 20 digit id for lender ID
			fake_data['LoanNumber'].append(loan_num) # TODO: 25 digit numeric number generated here
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
			
			PRIMARY KEY (TID, EarnDate, TaxYear),
			FOREIGN KEY (TID)
			REFERENCES Taxpayer(TID)
			ON DELETE CASCADE ON UPDATE CASCADE
		);

	TODO: finish and test Earnings
	'''
	def Earnings(self):

		count = int(input("enter number of rows to generate: "))

		fake_data = defaultdict(list)

		for x in range(count):
			
			id = str(x); # 20 digit numeric

			for i in range(20 - len(str(x))):

				id += str(faker.random_digit())


			data_set = ['address', 'name']
			line = faker.profile(fields=data_set)

			fake_data["TID"].append(id) # TODO: TID needs to be a foreign key/derived value
			fake_data["EarnDate"].append(faker.date(pattern='%d-%m-%Y'))
			fake_data["TaxYear"].append(faker.year()) # TODO: this maybe should be the current year...
			fake_data['Amount'].append(faker.random_int(1,1000000))
			
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

	TODO: finish and test EmploymentEarnings()
	'''
	def EmploymentEarnings(self):

		count = int(input("enter number of rows to generate: "))

		fake_data = defaultdict(list)

		for x in range(count):
			
			id = str(x); # 20 digit numeric

			for i in range(20 - len(str(x))):

				id += str(faker.random_digit())


			data_set = ['address', 'name']
			line = faker.profile(fields=data_set)

			fake_data["TID"].append(id) # TODO: TID needs to be a foreign key/derived value
			fake_data["EarnDate"].append(faker.date(pattern='%d-%m-%Y'))
			fake_data["TaxYear"].append(faker.year()) # TODO: this maybe should be the current year...
			fake_data['Amount'].append(faker.random_int(1,1000000))
			fake_data['TaxWithheld'].append(faker.random_int(0, 10)) # TODO: 'TaxWithheld' should be some percentage
			fake_data['EmployerID'].append(id) # TODO: another example of a foreign key in the table 20 digit numeric
			
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

		TODO: test RentEarnings()
	'''
	def RentEarnings(self):

		count = int(input("enter number of rows to generate: "))

		fake_data = defaultdict(list)

		for x in range(count):
			
			id = str(x); # 20 digit numeric

			for i in range(20 - len(str(x))):

				id += str(faker.random_digit())

			fake_data["TID"].append(id) # TODO: TID needs to be a foreign key/derived value
			fake_data["EarnDate"].append(faker.date(pattern='%d-%m-%Y'))
			fake_data["TaxYear"].append(faker.year()) # TODO: this maybe should be the current year...
			fake_data['HomeID'].append(id) # TODO: another example of a foreign key in the table 20 digit numeric
			fake_data['RenterID'].append(id) # TODO: another example of a foreign key in the table 20 digit numeric
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

		TODO: finish and test WorksFor()
	'''
	def WorksFor(self):

		count = int(input("enter number of rows to generate: "))

		fake_data = defaultdict(list)

		for x in range(count):
			
			id = str(x); # 20 digit numeric

			for i in range(20 - len(str(x))):

				id += str(faker.random_digit())


			data_set = ['address', 'name']
			line = faker.profile(fields=data_set)

			fake_data["EmployerID"].append(id) # TODO: TID needs to be a unique value
			fake_data["TID"].append(id) # TODO: TID is a foreign key referencing Taxpayer
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

	TODO: finish and test Owns()
	'''
	def Owns(self):

		count = int(input("enter number of rows to generate: "))

		fake_data = defaultdict(list)

		for x in range(count):
			
			id = str(x); # 20 digit numeric

			for i in range(20 - len(str(x))):

				id += str(faker.random_digit())


			fake_data["OID"].append(id) # TODO: OID needs to be foreign key from table
			fake_data["HomeID"].append(id) # TODO: TID is a foreign key referencing Taxpayer
			
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
	TODO: finish and test Rents()
	'''
	def Rents(self):

		count = int(input("enter number of rows to generate: "))

		fake_data = defaultdict(list)

		for x in range(count):
			
			id = str(x); # 20 digit numeric

			for i in range(20 - len(str(x))):

				id += str(faker.random_digit())


			fake_data["RenterID"].append(id) # TODO: OID needs to be foreign key from table
			fake_data["OwnerID"].append(id) # TODO: TID is a foreign key referencing Taxpayer
			fake_data['PropertyID'].append(id) # TODO: Foreign Key references rental properties(SID)
			fake_data['RentPerMonth'].append(faker.random_int(1, 1000000))
			fake_data['StartDate'].append(faker.date(pattern='%d-%m-%Y'))
			
		print('data placed in dictionary')

		return fake_data


start = Possum('bookbiz')
