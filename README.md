# BrassTax
Website for viewing tax information, performing tax calculations, and querying past tax filings. Used for CS360 Introduction to Database Design semester project. Contains code for the project and gives version control across all our systems. Meant to increase stability so that if somebody has a system failure it doesn't wreck all other project members.

## Guidelines 
1. Main branch should contain ***deployable code only***. Fork for personal work and adding features.
2. Don't commit dependencies or at least tell everybody asap and don't do it on the main branch without warning.
3. Don't use passwords or stuff that will lock people out of stuff. Not cool.
4. See this [link](https://dev.to/datreeio/top-10-github-best-practices-3kl2) for more information and some general best practices.

## Installing and Running `possum.py`

##### Installation
Possum is a commandline program capable of generating fake test data for the schema of this project. It is written in python and uses the following libraries which must be installed before running.

1. [Faker](https://faker.readthedocs.io/en/master/index.html)
2. [Pandas](https://pandas.pydata.org/pandas-docs/stable/index.html)
3. [SQLAlchemy](https://docs.sqlalchemy.org/en/13/index.html)

Install them via commandline with:

		pip3 install faker, pandas, sqlalchemy

More details can be found in this [article](towardsdatascience.com/generating-random-data-into-a-database-using-python-fd2f7d54024e).

_**Warning**_: As of 10/20/2020 one of latest versions of the libraries used in the article above was not supported on Windows 10. The workaround for this is to downgrade the library using `pip3`. Keep this in mind if you get weird errors during installation.

##### Running
Possum takes no command line args. Just run with:

		python3 possum.py

From here the first options you are presented with are for connecting to your server and database. If modifying a database on your machine choose the default options(user=root, server=localhost). After setting up the connection, you will be asked if you want to generate data for one of the tables(options 1-13) or all of them(option 14). Options 1-3 will generate complete data, but will not suport foreign keys for the various tables. If you want relations with reasonably accurate foreign keys, use option 14.

When the program is done and all tables have been uploaded to the server it should stop execution.

