/* 
 * Team has groupid, name. The intention of completion is to record the date teams complete the race, but is not implemented yet.
 * Primary key is groupid to allow group names to be changed.
 */
CREATE TABLE Team (
  GroupId NUMBER(4,0) PRIMARY KEY,
  GroupName VARCHAR(30) UNIQUE,
  Completion DATE
);

/* 
 * Participant has email, name and curpassword. Previous password was set up to potenitally allow a password reset feature that was not implemented
 * Email is groupid as all participants have different emails.
 */
CREATE TABLE Participant (
  Email VARCHAR(30) PRIMARY KEY,
  Name VARCHAR(40),
  curPassword VARCHAR(255) NOT NULL,
  prevPassword VARCHAR(255)
);

/*
 * Members has user emails and groupids. Foreign key is used to make sure valid participants and emails are used in teams
 * Email is primary key as a participant can only be in one group.
 */
CREATE TABLE Members (
  GroupId NUMBER(4,0),
  UserEmail VARCHAR(30) PRIMARY KEY,
  FOREIGN KEY (GroupId) REFERENCES Team(GroupId),
  FOREIGN KEY (UserEmail) REFERENCES Participant(Email)
);

/*
 * Race progress is tied to a user at a date, so this combination is primary key.
 * Progress for the user at the date include swimming, biking, and running.
 * Foreign key is used to make sure the participant is valid
 */
CREATE TABLE Race_Progress (
  UserEmail VARCHAR(30),
  Time DATE,
  Swimming NUMBER(3,2),
  Biking NUMBER(5,2),
  Running NUMBER(4,2),
  PRIMARY KEY (UserEmail, Time),
  FOREIGN KEY (UserEmail) REFERENCES Participant(Email)
);

/*
 * Team requests are used to manage user requesting to join a group, so the combination is the primary key.
 * Foreign key is used to make sure the participant is valid.
 * Group name is not a foreign key to allow create team requests
 */
CREATE TABLE Team_Requests (
  GroupName VARCHAR(30),
  UserEmail VARCHAR(30),
  PRIMARY KEY(GroupName, UserEmail),
  FOREIGN KEY (UserEmail) REFERENCES Participant(Email)
);

/*
 * This is used to manage requests to change group name.
 * Primary key is groupid as each group can only have one request to change name
 */
CREATE TABLE Group_Requests (
  GroupId NUMBER(4,0) PRIMARY KEY,
  GroupName VARCHAR(30)
);
