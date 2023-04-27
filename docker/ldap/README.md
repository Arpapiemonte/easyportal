do- LDAP **admin** password: `mypass`

test users (all with password: mypass):
- **aliccoop**
- **billidol**
- **carlbrun**

this is the procedure used once and for all to generate the keys and certificates in compose/ldap/certs:

    openssl dhparam 2048 > compose/ldap/certs/dhparam.pem
    # generate a CA private key
    certtool --generate-privkey --outfile compose/ldap/certs/ca.key
    # generate a self-signed CA certificate
    certtool --template ca.cfg --generate-self-signed --load-privkey compose/ldap/certs/ca.key --outfile compose/ldap/certs/ca.crt
    # generate the TLS private key
    certtool --generate-privkey --outfile compose/ldap/certs/ldap.key
    # generate the TLS certificate signed by the CA
    certtool --template ldap.cfg --generate-certificate --load-privkey compose/ldap/certs/ldap.key --outfile compose/ldap/certs/ldap.crt --load-ca-certificate compose/ldap/certs/ca.crt --load-ca-privkey compose/ldap/certs/ca.key

where ca.cfg is:

    cn = "ca"
    dc = "ldap"
    expiration_days = 3650
    signing_key
    encryption_key
    cert_signing_key
    ca

and ldap.cfg is:

    cn = "ldap"
    dc = "ldap"
    expiration_days = 3650
    signing_key
    encryption_key

clean up and launch the ldap service with:

    docker-compose -f docker-compose.yml -f docker-compose.local.yml down -v
    docker-compose -f docker-compose.yml -f docker-compose.local.yml up ldap

test the secure connection from within the ldap service (for debugging, add -d 9 option):

    docker-compose -f docker-compose.yml -f docker-compose.local.yml exec ldap ldapsearch -x -H ldaps://ldap:636 -b dc=ldap -D "cn=admin,dc=ldap" -w mypass

test the secure connection from the host:

    sudo apt install ldap-utils ca-certificates
    # manually add ldap to 127.0.0.0 in /etc/hosts:
    # 127.0.0.1       localhost ... ldap
    # this is dangerous ! do at your own risk
    sudo cp compose/ldap/certs/ca.crt /usr/local/share/ca-certificates/.
    sudo update-ca-certificates
    ldapsearch -d 9 -x -H ldaps://ldap:636 -b dc=ldap -D "cn=admin,dc=ldap" -w mypass
