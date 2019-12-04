CREATE OR REPLACE PROCEDURE
createTeam
  (
    groupName IN team.groupname%type,
    userEmail IN members.useremail%type
  )
IS
gId number;
bool number;
BEGIN 
  bool := 1;
  WHILE bool > 0
  LOOP
  	SELECT CAST(dbms_random.value * 10000 AS number) INTO gId FROM dual;
  	SELECT COUNT(*) INTO bool FROM Team WHERE GroupId = gId;
  END LOOP;
  
  INSERT INTO team VALUES (gId, groupName, NULL);
  INSERT INTO members VALUES (gId, userEmail);
END;
/

CREATE OR REPLACE FUNCTION joinTeamStatus
(
  groupName0 IN team.groupname%type,
  userEmail0 IN members.useremail%type
) RETURN number
IS
  numMembers number;
  gID number;
  PRAGMA AUTONOMOUS_TRANSACTION;
BEGIN
  SELECT groupId INTO gId FROM team WHERE groupName = groupName0;
  SELECT COUNT(*) INTO numMembers FROM members WHERE groupId = gId;
  IF (numMembers < 3)
  THEN
    INSERT INTO Members VALUES (gId,userEmail0);
  END IF;
  COMMIT;
  RETURN numMembers;
END;
/
