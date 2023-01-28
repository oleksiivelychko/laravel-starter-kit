#!/bin/bash

certs_dir=${PWD%/*}/certs

echo "🔑 Certificates (wildcard) are going to save into ${certs_dir} directory."
echo "💡 After that add 'localhost.crt' to 'Keychain Access' with 'Always Trust' option."

openssl req -x509 -days 365 -out ${certs_dir}/localhost.crt -keyout ${certs_dir}/localhost.key \
      -newkey rsa:2048 -nodes -sha256 \
      -subj '/CN=laravel-starter-kit.local' -extensions EXT -config <( \
       printf "[dn]\nCN=laravel-starter-kit.local\n[req]\ndistinguished_name = dn\n[EXT]\nsubjectAltName=DNS:laravel-starter-kit.local,DNS:mail.laravel-starter-kit.local\nkeyUsage=digitalSignature\nextendedKeyUsage=serverAuth")


if [ "$1" == "add-hosts" ]; then
    echo 127.0.0.1 laravel-starter-kit.local mail.laravel-starter-kit.local >> /etc/hosts
fi


