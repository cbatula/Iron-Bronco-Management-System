/* Make some sample groups */
INSERT INTO Team VALUES (0,'A',NULL);
INSERT INTO Team VALUES (1,'B',NULL);

/* Make some sample participants */
INSERT INTO Participant VALUES ('a1@scu.edu','Aria','password',NULL);
INSERT INTO Participant VALUES ('a2@scu.edu','Beth','password',NULL);
INSERT INTO Participant VALUES ('a3@scu.edu','Claire','password',NULL);
INSERT INTO Participant VALUES ('a4@scu.edu','Daisy','password',NULL);
INSERT INTO Participant VALUES ('a5@scu.edu','Eva','password',NULL);
INSERT INTO Participant VALUES ('a6@scu.edu','Fiona','password',NULL);
INSERT INTO Participant VALUES ('a7@scu.edu','Georgia','password',NULL);
INSERT INTO Participant VALUES ('a8@scu.edu','Hailey','password',NULL);
INSERT INTO Participant VALUES ('a9@scu.edu','Iris','password',NULL);
INSERT INTO Participant VALUES ('a10@scu.edu','Jessica','password',NULL);
INSERT INTO Participant VALUES ('a11@scu.edu','Keeley','password',NULL);

/* Put some particpants in groups */
-- 1 group filled
INSERT INTO Members VALUES (0,'a1@scu.edu');
INSERT INTO Members VALUES (0,'a2@scu.edu');
INSERT INTO Members VALUES (0,'a3@scu.edu');
-- 1 group not completely filled
INSERT INTO Members VALUES (1,'a4@scu.edu');
INSERT INTO Members VALUES (1,'a5@scu.edu');

/* Put some participants that will be assigned to groups */
INSERT INTO Participant_Not_In_Group VALUES ('a6@scu.edu');
INSERT INTO Participant_Not_In_Group VALUES ('a7@scu.edu');
INSERT INTO Participant_Not_In_Group VALUES ('a8@scu.edu');
INSERT INTO Participant_Not_In_Group VALUES ('a9@scu.edu');

/* Give some sample race progress */
INSERT INTO Race_Progress VALUES ('a1@scu.edu',date '2019-11-01',0.1,0.2,0.3);
INSERT INTO Race_Progress VALUES ('a1@scu.edu',date '2019-11-02',0.2,0.3,0.4);
INSERT INTO Race_Progress VALUES ('a2@scu.edu',date '2019-11-01',0.1,0.2,0.3);
INSERT INTO Race_Progress VALUES ('a2@scu.edu',date '2019-11-02',0.2,0.3,0.4);
INSERT INTO Race_Progress VALUES ('a3@scu.edu',date '2019-11-01',0.1,0.2,0.3);
INSERT INTO Race_Progress VALUES ('a3@scu.edu',date '2019-11-02',0.2,0.3,0.4);
INSERT INTO Race_Progress VALUES ('a4@scu.edu',date '2019-11-01',0.3,0.3,0.3);
INSERT INTO Race_Progress VALUES ('a4@scu.edu',date '2019-11-02',0.4,0.4,0.4);
INSERT INTO Race_Progress VALUES ('a5@scu.edu',date '2019-11-01',0.3,0.3,0.3);
INSERT INTO Race_Progress VALUES ('a5@scu.edu',date '2019-11-02',0.4,0.4,0.4);

/* Give some sample team requests */
INSERT INTO Team_Requests VALUES (1,'a10@scu.edu');
INSERT INTO Team_Requests VALUES (1,'a11@scu.edu');
