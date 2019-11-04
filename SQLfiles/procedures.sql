CREATE OR REPLACE PROCEDURE
createTeam
  (
    groupName IN team.groupname%type,
    userEmail IN members.useremail%type
  )
IS
gId number;
BEGIN
  SELECT COUNT(*)
  INTO gId
  FROM team;
  INSERT INTO team VALUES (gId, groupName, NULL);
  INSERT INTO members VALUES (gId, userEmail);
END;
/

CREATE OR REPLACE PROCEDURE
joinTeam
(
  groupName0 IN team.groupname%type,
  userEmail0 IN members.useremail%type
)
IS
numMembers number;
gID number;
BEGIN
  SELECT groupId INTO gId FROM team WHERE groupName = groupName0;
  SELECT COUNT(*) INTO numMembers FROM members WHERE groupId = gId;
  IF (numMembers < 3)
  THEN
    INSERT INTO Members VALUES (gId,userEmail0);
  END IF;
END;
/

CREATE OR REPLACE FUNCTION joinTeamStatus
(
  groupName0 IN team.groupname%type,
  userEmail0 IN members.useremail%type
) RETURN number
IS
  numMembers numer;
  gID number;
BEGIN
  SELECT groupId INTO gId FROM team WHERE groupName = groupName0;
  SELECT COUNT(*) INTO numMembers FROM members WHERE groupId = gId;
  IF (numMembers < 3)
  THEN
    INSERT INTO Members VALUES (gId,userEmail0);
  END IF;
  RETURN numMembers;
END;
/
