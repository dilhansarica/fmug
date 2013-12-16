#!/bin/sh

JS_ROOT="../www/js"
MJSB_ROOT="$JS_ROOT/lib/mjsb"

cp $MJSB_ROOT/require/i18n.js $MJSB_ROOT/
cp -r $JS_ROOT/i18n $MJSB_ROOT/nls

cp -r $JS_ROOT/app $MJSB_ROOT/
cp -r $JS_ROOT/lib/project $MJSB_ROOT/
cp -r $JS_ROOT/lib/common $MJSB_ROOT/

./bin/node ./bin/createcompress.js $JS_ROOT/
./bin/node ./bin/r.js -o $MJSB_ROOT/compress.js baseUrl=$MJSB_ROOT

rm -r $MJSB_ROOT/i18n.js $MJSB_ROOT/compress.js $MJSB_ROOT/app $MJSB_ROOT/project $MJSB_ROOT/common $MJSB_ROOT/nls
