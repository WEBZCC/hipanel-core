# Directory Structure

- commited files:
    - `core` -> vendor/hiqdev/%the-core-package%/
    - `composer.json`
    - `composer.lock` - commited at releases
    - `hidev.yml`
    - `config/`
        - `params.php` - normally not needed or minimal
    - `.docker/nginx/etc/nginx/conf.d/vhost.conf` - almost standart but doesn't work when symlinked :(
    - `.env.dist` - production config
    - `.env.local` - local installation config
    - `.gitignore`
    - `public/`
        - `assets/`
            - `.gitignore`
        - `favicon.ico`
        - `index.php` -> ../core/public/index.php
        - `robots.txt` -> ../core/public/robots.txt
    - `runtime/`
        - `.gitignore`
    - `phpunit.xml.dist` -> core/phpunit.xml.dist
    - `tests/` -> core/tests
    - `README.md`
    - `LICENSE`
    - `ssl/` - normally not needed because automated
        - `fullchain.pem`
        - `privkey.pem`
- uncommited files:
    - `docker-compose.yml` -> 
        - `core/docker-compose.yml.dist` for **production**
        - `core/docker-compose.yml.local` for **local** development
        - `core/docker-compose.yml.dev` for **staging** server
    - `.env` -> .env.dist

## Ideas

- keep root package as DRY as possible to avoid the need for propagating changes in all the roots
- that's  why `core` symlink to core package:
    - `hiqdev/hipanel-core` in **hipanel**
    - `hiqdev/hipanel-site` in **site**
    - `hiqdev/hiam`         in **hiam**
    - `hiqdev/hiapi-legacy` in **hiapi**
- standart files and dirs symlinked from core, reusable between all installations:
    - public/index.php
    - public/robots.txt
    - tests/
    - phpunit.xml.dist
    - docker-compose.yml
- thouroughly handcrafted files with project configuration:
    - hidev.yml
    - composer.json
    - .env.dist - production config
    - .env.local - config for local isntallation
    - .env.dev - staging
    - config/params.php - not needed or minimal
    - docker-compose.yml - normally is a symlink, but may be deriviated from `core/docker-compose.yml.(dist|dev|local)`
- hidev generated files:
    - README.md
    - LICENSE
    - .gitignore
- evolving files:
    - public/assets/ - needs chmod a+w
    - runtime/ - needs chmod a+w
    - composer.lock - commited at releases
    - ssl/ - normally not needed
- copy pasted files, to be automated:
    - .docker/nginx/etc/nginx/conf.d/vhost.conf - can include IP restrictions
    - favicon.ico
