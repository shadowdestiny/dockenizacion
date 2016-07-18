-- Archiving job to keep the matching table small and improve performance. These should be run right after the analysis queries. 
insert into matchHistory
(`matchDate`, 
`userID`, 
`lPrize`, 
`providerBetID`, 
`matchTypeID`, 
`drawDate`, 
`rPrize`, 
`matchStatus`,
`prizeCurrencyName`
) 

select 
	m1.`matchDate`,
	m1.userID,
	m1.prize,
	m1.providerBetID,
	m1.matchTypeID,
	m1.drawDate,
	m2.prize,
	m1.matchstatus,
	m1.prizeCurrencyName
from matcher m1, matcher m2
where m1.matchID = m2.`ID`
and m1.matchSide = 'l'
and m2.matchside = 'r'
and m1.matchID is not null
and m1.matchID is not null;

-- Delete from matcher table if we have successfully inserted into matchHistory
delete from matcher  
where providerBetID in (
	select h.providerBetID 
	from matchHistory h
	where
		h.providerBetID = providerBetID and
		h.matchTypeID = matchTypeID and
		h.drawDate = drawDate
);

