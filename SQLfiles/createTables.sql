CREATE TABLE Team (
  GroupId NUMBER(4,0) PRIMARY KEY,
  GroupName VARCHAR(30) UNIQUE,
  Completion DATE
);

CREATE TABLE Participant (
  Email VARCHAR(30) PRIMARY KEY,
  Name VARCHAR(40),
  curPassword VARCHAR(255) NOT NULL,
  prevPassword VARCHAR(255)
);

CREATE TABLE Members (
  GroupId NUMBER(4,0),
  UserEmail VARCHAR(30),
  PRIMARY KEY(GroupId, UserEmail),
  FOREIGN KEY (GroupId) REFERENCES Team(GroupId),
  FOREIGN KEY (UserEmail) REFERENCES Participant(Email)
);

CREATE TABLE Race_Progress (
  UserEmail VARCHAR(30),
  Time DATE,
  Swimming NUMBER(3,2),
  Biking NUMBER(5,2),
  Running NUMBER(4,2),
  PRIMARY KEY (UserEmail, Time),
  FOREIGN KEY (UserEmail) REFERENCES Participant(Email)
);

CREATE TABLE Participant_Not_In_Group (
  Email VARCHAR(30) PRIMARY KEY,
  FOREIGN KEY (Email) REFERENCES Participant(Email)
);

CREATE TABLE Team_Requests (
  GroupId NUMBER(4,0),
  UserEmail VARCHAR(30),
  PRIMARY KEY(GroupId, UserEmail),
  FOREIGN KEY (GroupId) REFERENCES Team(GroupId),
  FOREIGN KEY (UserEmail) REFERENCES Participant(Email)
);

CREATE TABLE Group_Requests (
  GroupId NUMBER(4,0) PRIMARY KEY,
  GroupName VARCHAR(30);
)
/*

Current tables 

OWNER			       TABLE_NAME
------------------------------ ------------------------------
LSHEN			       TOTALS_6
LSHEN			       TEST
LSHEN			       SUPERVISOR
LSHEN			       SUMMERJOB
LSHEN			       STUDENT_JOB
LSHEN			       STUDENT
LSHEN			       SCHEDULE
LSHEN			       RENTER
LSHEN			       RENTAL_PROPERTY
LSHEN			       PROPERTY_OWNER
LSHEN			       ORDER_ITEM

OWNER			       TABLE_NAME
------------------------------ ------------------------------
LSHEN			       MEALORDER
LSHEN			       MEALITEM
LSHEN			       MANAGER
LSHEN			       L_EMP
LSHEN			       L_DEPT
LSHEN			       LEASE_AGREEMENT
LSHEN			       ITEMORDER
LSHEN			       FORMTEST
LSHEN			       EXPENSES
LSHEN			       EVENTS
LSHEN			       EMP_WORK

OWNER			       TABLE_NAME
------------------------------ ------------------------------
LSHEN			       EMPSTATS
LSHEN			       EMPLOYEE
LSHEN			       DELIVERYSERVICE
LSHEN			       CUSTOMER
LSHEN			       COURSE_PREREQ
LSHEN			       COURSE
LSHEN			       BRANCH
LSHEN			       BOOK_HIGHLIGHTS
LSHEN			       BOOKS_READ
LSHEN			       BANKCUST_6
LSHEN			       ALPHACOEMP

OWNER			       TABLE_NAME
------------------------------ ------------------------------
LSHEN			       ACTIVITIES
LSHEN			       ACCOUNTS_6
LSHEN			       STAFF_2010

#/
