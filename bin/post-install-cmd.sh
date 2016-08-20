#!/bin/bash

if [ ! -f app/config/parameters.yml ]
then
    cp app/config/parameters.yml.dist app/config/parameters.yml
fi

if [ ! -f ~/.config/webhelper/parameters.yml ]
then
    mkdir -p ~/.config/webhelper/
    cp app/config/parameters.yml.dist  ~/.config/webhelper/parameters.yml
fi
