<?php

namespace dhope0000\LXDClient\Tools\Hosts\Certificates;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Model\Hosts\UpdateHost;

class RenewCert
{
    private $updateHost;

    private $certSettings = [
        "countryName"            => "UK",
        "stateOrProvinceName"    => "London",
        "localityName"           => "London",
        "organizationName"       => "LxdMosaic",
        "organizationalUnitName" => "Prod",
        "commonName"             => "127.0.0.1",
        "emailAddress"           => "hello@lxdmsoaic.com"
    ];

    public function __construct(UpdateHost $updateHost)
    {
        $this->updateHost = $updateHost;
    }

    public function renew(Host $host)
    {
        $certificate = $this->generateCert();

        $host->certificates->add($certificate["combined"], null, "LxdMosaic");

        $this->writeCert($host, $certificate);

        $this->updateHost->updateCertificateDetails(
            $host->getHostId(),
            $host->getAlias() . ".key",
            $host->getAlias() . ".cert",
            $host->getAlias() . ".combined",
        );

        return true;
    }

    private function generateCert()
    {
        // Generate certificate
        $privkey = openssl_pkey_new();
        $cert    = openssl_csr_new($this->certSettings, $privkey);
        $cert    = openssl_csr_sign($cert, null, $privkey, 365);

        // Generate strings
        openssl_x509_export($cert, $certString);
        openssl_pkey_export($privkey, $privkeyString);

        return [
            "key"=>$privkeyString,
            "cert"=>$certString,
            "combined"=>$certString.$privkeyString
        ];
    }

    private function writeCert($host, $cert)
    {
        if (file_put_contents($_ENV["LXD_CERTS_DIR"] . $host->getAlias() . ".key", $cert["key"])  != true) {
            throw new \Exception("Couldn't store key file", 1);
        }

        if (file_put_contents($_ENV["LXD_CERTS_DIR"] . $host->getAlias() . ".cert", $cert["cert"]) != true) {
            throw new \Exception("Couldn't store cert file", 1);
        }

        if (file_put_contents($_ENV["LXD_CERTS_DIR"] . $host->getAlias() . ".combined", $cert["combined"]) != true) {
            throw new \Exception("Couldn't store combined file", 1);
        }

        return true;
    }
}
