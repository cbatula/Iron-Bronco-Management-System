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
  UserEmail VARCHAR(30) PRIMARY KEY,
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

CREATE TABLE Team_Requests (
  GroupName VARCHAR(30),
  UserEmail VARCHAR(30),
  PRIMARY KEY(GroupName, UserEmail),
  FOREIGN KEY (UserEmail) REFERENCES Participant(Email)
);

CREATE TABLE Group_Requests (
  GroupId NUMBER(4,0) PRIMARY KEY,
  GroupName VARCHAR(30)
);
