#!/bin/bash

echo $1
echo $2

sudo echo -e "$2\n$2" | sudo passwd $1

