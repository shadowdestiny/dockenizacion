#!/usr/bin/env bash
git clone https://github.com/PanamediaSLU/euromillions.git src
chmod 777 src/app/assets
chmod 777 src/app/cache
mkdir .git/hooks
cp githooks/* .git/hooks
cd vagrant
vagrant up
