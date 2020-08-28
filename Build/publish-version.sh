#!/bin/sh

if [ -n "${VERSION}" ] ; then
    true # pass
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

rm -rf Release

mkdir Release
pushd Release

mkdir extension
unzip ../web2pdf_*.zip -d extension

composer -n init 
composer -n require --dev helhum/ter-client 'dev-master#2afdb1a04c0975a31ab4450daed732bc5f84ea7f'

php -d default_socket_timeout=3600 vendor/bin/ter-client upload -u "${TER_USERNAME}" -p "${TER_PASSWORD}" -m "Release version ${VERSION}" web2pdf ./extension