#!/bin/bash

source config/environment.sh

cd sql
psql < add_test_data.sql
