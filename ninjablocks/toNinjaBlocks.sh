#!/bin/bash

# Bash wrapper for toNinjaBlocks.php

# Note: Async to a few things can be published at a time

while read line; do
        php toNinjaBlocks.php "$line" &
done;
