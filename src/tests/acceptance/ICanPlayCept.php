<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('go to the play page');
$I->amOnPage('/');
$I->click('Play Now');
$I->see('Choose 5 numbers & 2 stars to play');

