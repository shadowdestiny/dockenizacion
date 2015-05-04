#!/usr/bin/env bash
sleep 30
mysql -u root -ptpl9 euromillions < /dbinit/init_structure.sql
cat /dbinit/data/*.sql | mysql -u root -ptpl9 euromillions --default-character-set=utf8
