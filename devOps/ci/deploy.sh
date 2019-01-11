#!/usr/bin/env bash

sudo docker tag panamedialottery/euromillions-php:cron_${BUILD_IMAGE_TAG} panamedialottery/euromillions-php:cron_${EM_ENVIRONMENT}
sudo docker push panamedialottery/euromillions-php:cron_${EM_ENVIRONMENT}

sudo docker tag panamedialottery/euromillions-php:${BUILD_IMAGE_TAG} panamedialottery/euromillions-php:${EM_ENVIRONMENT}
sudo docker push panamedialottery/euromillions-php:${EM_ENVIRONMENT}

sudo docker tag panamedialottery/euromillions-web:${BUILD_IMAGE_TAG} panamedialottery/euromillions-web:${EM_ENVIRONMENT}
sudo docker push panamedialottery/euromillions-web:${EM_ENVIRONMENT}
