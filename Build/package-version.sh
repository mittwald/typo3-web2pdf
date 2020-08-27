#!/bin/sh

if [ -n "${VERSION}" ] ; then
    # pass
elif [ -n "${GITHUB_REF}" ] ; then
    VERSION="${GITHUB_REF/refs\/tags\//}"
    VERSION="${VERSION#v}"
elif [ -n "${TRAVIS_TAG}" ] ; then
    VERSION="${TRAVIS_TAG#v}"
fi

# Else, try some magic to determine the version from variables that a CI system
# might set.
if [ -z "${VERSION}" ] ; then
    echo "could not determine version; make sure either VERSION, GITHUB_REF or TRAVIS_TAG is set" >&2
    exit 1
fi

set -e

rm -rf Tests
rm .gitignore
rm -rf .github

pushd Resources/Private/Libraries
composer install --no-dev
rm -rf vendor/mpdf/mpdf/ttfonts

popd

sed -i -e "s,[0-9]\.[0-9]-dev,${VERSION},g" ext_emconf.php
zip -9 -r --exclude=.git/* --exclude=.Build/* web2pdf_${VERSION}.zip .