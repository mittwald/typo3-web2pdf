#!/bin/sh

if [ -n "${VERSION}" ] ; then
    true # pass
elif [ -n "$1" ] ; then
    VERSION="$1"
elif [ -n "${GITHUB_REF}" ] ; then
    VERSION="${GITHUB_REF/refs\/tags\//}"
    VERSION="${VERSION#v}"
elif [ -n "${TRAVIS_TAG}" ] ; then
    VERSION="${TRAVIS_TAG#v}"
fi

if [ -z "${VERSION}" ] ; then
    echo "could not determine version; make sure either VERSION, GITHUB_REF or TRAVIS_TAG is set" >&2
    exit 1
fi

set -e

pushd Resources/Private/Libraries
composer install --no-dev

popd

zip -9 -r \
    --exclude=Resources/Private/Libraries/vendor/mpdf/mpdf/ttfonts/* \
    web2pdf_${VERSION}.zip \
    Classes \
    Configuration \
    Documentation \
    Resources \
    ext_emconf.php \
    ext_icon.png \
    ext_localconf.php \
    LICENSE.txt \
    README.md \
    composer.json \