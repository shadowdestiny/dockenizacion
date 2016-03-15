<?php
$I = new FunctionalTester($scenario);
$I->wantTo('ensure that frontpage works');
$I->amOnPage('/');
$I->see('Home');