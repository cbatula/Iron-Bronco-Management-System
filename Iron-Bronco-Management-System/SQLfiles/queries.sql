-- Register for Iron Bronco
INSERT INTO Participant VALUES (:email,:name,:password,NULL);

-- Task 2a: Create a Team

/*
This is done inside Procedure CreateTeam
SELECT COUNT(*) FROM Team;
INSERT INTO Team VALUES ($GroupId,$GroupName,NULL);
INSERT INTO Members VALUES ($GroupId,$Email);
*/

-- BEGIN createteam(:groupname,:email); END;
INSERT INTO Team_requests VALUES (:groupname, :email);

-- Task 2b: Join a team

/*
Groupname is string
Email is string
In procedure jointeam:
  SELECT groupId INTO gId FROM team WHERE groupName = groupName0;
  SELECT COUNT(*) INTO numMembers FROM members WHERE groupId = gId;
  IF (numMembers < 3)
  THEN
    INSERT INTO Members VALUES (gId,userEmail0);
  END IF;
*/

--BEGIN jointeam(:groupname,:useremail); END;
INSET INTO Team_requests VALUES (:groupname, :email); 

-- Task 3: View and submit Team Progress

/*
$Groupid is integer
*/
-- View Team Progress
SELECT SUM(swimming), SUM(biking),SUM(running) FROM race_progress INNER JOIN members ON race_progress.useremail = members.useremail WHERE groupid = :GroupId;

/*
$swimToday, $bikeToday, $runToday are numbers
$Email is string
$day is properly formatted date as 'YYYY-MM-DD'
*/

--Update Team Progress
UPDATE race_progress
SET swimming = :swimToday, biking = :bikeToday, running = :runToday
WHERE useremail = $Email AND time = date $day;

--Admin removing team members
DELETE FROM Members WHERE useremail = :email;

--Admin approving team name
DELETE FROM Group_requests WHERE groupId = :groupId AND groupName = :groupName;
UPDATE TEAM SET Groupname = :groupname WHERE groupId = :groupId;

--Admin approving starting a group
DELETE FROM Team_Requests WHERE useremeail = :email;
BEGIN createteam(:groupname,:email); END;

  --Admin approving joining a group
DELETE FROM Team_Requests WHERE useremail = :email;
BEGIN jointeam(:groupname,:useremail); END;
