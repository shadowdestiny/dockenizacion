<?php
$I = new FunctionalTester($scenario);
$I->wantTo('go to the play page');
$I->amOnPage('/');
$I->click('Play Now');
$I->see('Choose 5 numbers & 2 stars to play');