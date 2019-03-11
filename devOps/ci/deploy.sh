#!/usr/bin/env bash

sudo docker tag panamedialottery/euromillions-php:cron_${BUILD_IMAGE_TAG} panamedialottery/euromillions-php:cron_${TARGET_IMAGE_TAG}
sudo docker push panamedialottery/euromillions-php:cron_${TARGET_IMAGE_TAG}

sudo docker tag panamedialottery/euromillions-php:${BUILD_IMAGE_TAG} panamedialottery/euromillions-php:${TARGET_IMAGE_TAG}
sudo docker push panamedialottery/euromillions-php:${TARGET_IMAGE_TAG}

sudo docker tag panamedialottery/euromillions-web:${BUILD_IMAGE_TAG} panamedialottery/euromillions-web:${TARGET_IMAGE_TAG}
sudo docker push panamedialottery/euromillions-web:${TARGET_IMAGE_TAG}
