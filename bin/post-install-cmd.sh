#!/bin/bash

if [ ! -f app/config/parameters.yml ]
then
    cp app/config/parameters.yml.dist app/config/parameters.yml
fi
