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
