#!/usr/bin/env bash

NOW=$(date +'%Y%m%d_%H%M')
GIT_TAG=${NOW}_${BUILD_IMAGE_TAG}
GIT_COMMIT_MESSAGE=$(git log --format=format:%s -1 ${GIT_COMMIT})

# Set credentials
git config --local credential.helper "!p() { echo username=\\${GIT_CREDENTIALS_USR}; echo password=\\${GIT_CREDENTIALS_PSW}; }; p"

# Add a tag on git and push to origin
git tag -m "Release deployed:" -m "${GIT_COMMIT_MESSAGE}" ${GIT_TAG} ${GIT_COMMIT}
git push --tags origin ${GIT_TAG}

# Unset credentials
git config --unset credential.username
git config --unset credential.helper