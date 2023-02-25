#!/bin/bash

domains=( "$@" )
if [ ${#domains[@]} -eq 0 ]; then
    echo "Domains were not provided."
    exit 0
fi

mainDomain=${domains[0]}
dnsNames=""
dnsHosts=""

for i in "$@";
do
    dnsNames+="DNS:$i,"
    dnsHosts+="$i "
done

dnsNames=${dnsNames%?}
dnsHosts=${dnsHosts%?}

certs_dir="${CERTS_DIR:-${PWD}/certs}"

echo "🔑 Certificates (wildcard) are going to save into ${certs_dir} directory (or export CERTS_DIR)."
echo "💡 After that add 'localhost.crt' to 'Keychain Access' with 'Always Trust' option."

config="[dn]\nCN=${mainDomain}\n[req]\ndistinguished_name=dn\n[EXT]\nsubjectAltName=${dnsNames}\nkeyUsage=digitalSignature\nextendedKeyUsage=serverAuth"

openssl req -x509 -days 365 -out "${certs_dir}/localhost.crt" -keyout "${certs_dir}/localhost.key" \
      -newkey rsa:2048 -nodes -sha256 \
      -subj "/CN=${mainDomain}" -extensions EXT -config <(printf "%b\n" "${config}")

if grep -q "${dnsHosts}" /etc/hosts; then
    echo "❗️Domains already exist in /etc/hosts"
else
    printf "❗ Domains must be added like:\necho 127.0.0.1 %b >> /etc/hosts\n" "${dnsHosts}"
fi
