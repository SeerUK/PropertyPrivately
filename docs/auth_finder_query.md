
# Valid Auth Finding Query

Put in a username and it'll spit out some valid tokens combinations to use.

    SELECT 
        a.token AS ApplicationToken, 
        t.token AS UserToken
    FROM 
        User u 
    INNER JOIN
        Token t ON t.userId = u.id
    INNER JOIN
        Application a ON a.id = t.applicationId
    WHERE
        u.username = "SeerUK"
    ;
