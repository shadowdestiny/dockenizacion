
-- full match------------
create  table if not exists temp1 as (
select 
	ml.id as lID, 
	mr.id as rID,
	ml.providerBetId
from
(select * from matcher where matchside = 'l' and matchID is null) AS ml,
(select * from matcher where matchside = 'r' and matchID is null) AS mr
where 
	ml.providerBetID = mr.providerBetID AND
 	ml.prize = mr.prize AND
--	ml.balls = mr.balls AND
--	ml.stars = mr.stars
);

update matcher m
set 
	matchStatus = 'matched',
	matchID = (select rID from temp1 t where m.providerBetID=t.providerBetID and m.id=t.lID),
	matchdate = now()
	where m.matchID is null
	and m.providerBetID in (select providerBetID from temp1);

update matcher m
set 
	matchStatus = 'matched',
	matchID = (select lID from temp1 t where m.providerBetID=t.providerBetID and m.id=t.rID),
	matchdate = now()
	where m.matchID is null
	and m.providerBetID in (select providerBetID from temp1);

drop table temp1;



-- --Mismatch--------
create  table if not exists temp1 as (
select 
	ml.id as lID, 
	mr.id as rID,
	ml.providerBetId
from
(select * from matcher where matchside = 'l' and matchID is null) AS ml,
(select * from matcher where matchside = 'r' and matchID is null) AS mr
where 
	ml.providerBetID = mr.providerBetID AND
	(ml.prize != mr.prize) 
--	OR
--	ml.balls != mr.balls OR
--	ml.stars != mr.stars)
);

update matcher m
set 
	matchStatus = 'mismatched',
	matchID = (select rID from temp1 t where m.providerBetID=t.providerBetID and m.id=t.lID),
	matchdate = now()
	where m.matchID is null
	and m.providerBetID in (select providerBetID from temp1);

update matcher m
set 
	matchStatus = 'mismatched',
	matchID = (select lID from temp1 t where m.providerBetID=t.providerBetID and m.id=t.rID),
	matchdate = now()
	where m.matchID is null
	and m.providerBetID in (select providerBetID from temp1);

drop table temp1;


-- ---unmatched----------
create  table if not exists temp1 as (
select id from matcher 
where matchside = 'l' and matchID is null
AND providerBetID not in (select providerBetID from matcher m2 where m2.matchside = 'r' and m2.matchID is null));

update matcher m set matchStatus = 'unmatched',
matchdate = now() 
where m.id = (select t.id from temp1 t where t.id = m.id);

drop table temp1;

create  table if not exists temp1 as (
select id from matcher 
where matchside = 'r' and matchID is null
AND providerBetID not in (select providerBetID from matcher m2 where m2.matchside = 'l' and m2.matchID is null));

update matcher m set matchStatus = 'unmatched',
matchdate = now() 
where m.id = (select t.id from temp1 t where t.id = m.id);
	
drop table temp1;





