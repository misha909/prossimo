#!/bin/bash

# include config
# config example below:
#
#
# Example deploy_config.sh
#
# dev_env() {
#     PHPBIN="/usr/local/bin/php"
#     SERVER_IP="192.168.1.1"
#     SERVER_PORT="22"
#     SERVER_USER="apache"
#     HTTPDOCS_PATH="/var/www/vhosts/domain.com/httpdocs"
#     ASSETIC_ENV="--env=prod"
#     CERTIFICATE="certificate.pem"
# }

color_output() {
  echo -e "\033[$2m$1\033[0m"
}

execute_task() {
  echo ""
  color_output "-> started '$1'" "$START_TASK_COLOR"
  "$1"
  color_output "<- finished '$1'" "$END_TASK_COLOR"
}

skip_task() {
  echo "[SKIPPED] $1"
}

title_label() {
  color_output "$1" "$START_END_SCRIPT_COLOR"
}

fn_exists()
{
    [ `type -t $1`"" == 'function' ]
}

START_END_SCRIPT_COLOR=37
START_TASK_COLOR=33
END_TASK_COLOR=36


echo "Running deploy for $1 environment"
title_label "-> Deploy started at: $(date)"

echo ""

title_label "Deploy finished at: $(date)"

## Get new tags from remote
# sudo -u www-deployer git fetch --tags

## Get latest tag name
# latestTag=$(git describe --tags `git rev-list --tags --max-count=1`)

## Checkout latest tag
# sudo -u www-deployer git checkout $latestTag