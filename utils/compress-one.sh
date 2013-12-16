#!/bin/sh

./bin/jsmin < $1 > $1.tmp
rm $1
mv $1.tmp $1
