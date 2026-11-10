# PHP-XRAY-Demo

> **Intentionally vulnerable.** `composer.json` pins old package versions with known CVEs so JFrog Xray has something to find. Do not deploy this anywhere real.

A tiny PHP app (`/`, `/health`, `/versions`, `/token`) built into a Docker image (`php:7.4-apache-bullseye`). A GitHub Action builds it, scans it with Xray, pushes it to the Artifactory Docker repo `yalin-demo-xray-local` on `solenglatest.jfrog.io`, publishes build-info and runs an Xray build scan.

Pinned packages: `guzzlehttp/guzzle` 6.5.5, `guzzlehttp/psr7` 1.8.0, `twig/twig` 2.14.0, `firebase/php-jwt` 5.2.0, `laminas/laminas-diactoros` 2.0.0, `league/flysystem` 1.1.3, `phpseclib/phpseclib` 3.0.0, `smarty/smarty` 3.1.43 (the last three added in v1.1.0). Check exact CVEs and severities in Xray.

## Run locally

```bash
docker build -t php-xray-demo .
docker run --rm -p 8080:80 php-xray-demo
curl localhost:8080/health
```

## CI

Required repository secrets: `JF_USER`, `JF_TOKEN` (deploy permission on `yalin-demo-xray-local`).
The build name `PHP-XRAY-Demo` must be added to Xray indexing (Xray → Scans List → Builds → Add/Remove to Xray) or the build scan reports "not selected for indexing".
Image: `solenglatest.jfrog.io/yalin-demo-xray-local/php-xray-demo:<sha | latest | 1.1.0>`.
