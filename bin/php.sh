#!/usr/bin/env bash

DOCKER_COMPOSE_FILE=$(dirname "$(readlink -f "$0")")/../docker-compose.yaml
docker-compose -f ${DOCKER_COMPOSE_FILE} exec bolton php "$@"
