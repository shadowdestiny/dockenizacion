-- insert into matcher (matchTypeID,matchSide,userID,providerBetID,drawDate,prize,prizeCurrencyName)

select
	1,
	'l' ,
	t.user_id,
	lv.id_ticket,
	d.draw_date,
	SUBSTRING_INDEX(SUBSTRING_INDEX(data, '#', 3),'#',-1)/100,
	t.wallet_before_uploaded_currency_name
from
	transactions t,
	euromillions_draws d,
	bets b,
	log_validation_api lv 
WHERE
	SUBSTRING_INDEX(t.data, '#', 1) = d.id AND
	SUBSTRING_INDEX(t.data, '#', 2) = b.id AND
	b.id = lv.bet_id AND
	t.entity_type in ('big_winning','winnings_received') AND
	t.date >= curdate() 