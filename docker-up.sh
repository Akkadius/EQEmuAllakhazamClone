#!/usr/bin/env bash

cd laradock && docker-compose up -d nginx mariadb workspace && cd ..