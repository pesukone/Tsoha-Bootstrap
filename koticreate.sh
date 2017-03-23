#!/bin/bash

source config/environment.sh

cd sql
cat drop_tables.sql create_tables.sql | psql -1 -f -
