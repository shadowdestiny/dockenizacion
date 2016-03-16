<?php
$I = new FunctionalTester($scenario);
$I->wantTo('ensure that frontpage works even if crons didn\t');
$I->amOnPage('/');
$I->see('Home');