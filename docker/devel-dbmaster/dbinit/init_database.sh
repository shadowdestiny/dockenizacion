#!/usr/bin/env bash
sleep 20
mysql -u root -ptpl9 euromillions < /dbinit/init_structure.sql
mysql -u root -ptpl9 euromillions --default-character-set=utf8 < /dbinit/countries.sql
mysql -u root -ptpl9 euromillions --default-character-set=utf8 < /dbinit/translations.sql
mysql -u root -ptpl9 euromillions --default-character-set=utf8 < /dbinit/translation_details.sql
