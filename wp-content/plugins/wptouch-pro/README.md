## WPtouch Pro

This repo contains the code for WPtouch pro as well as WPtouch free. The free version is built by running the `deploy-free-plugin` shell script on the server and then deployed manually through SVN to the WordPress plugin repository.

The pro version is deployed by running the `deploy-plugin` shell script on the server. This will automatically create the package, send to s3, and update the local version numbers (on the server that the API uses to determine if a new release is available). 15-20 mins or so after this script is run, users will see a new version available for download in their admin.

 An example deploy might look like:

 * Add feature in `wptouch-pro`
 * Merge to master and deploy that to server (happens automatically thanks to Codeship)
 * Run `deploy-plugin` script to create necessary packages from the new source files and push out to all users.

## Codeship: Auto deploying plugin updates to the server

> Note: This repo has the `wptouch_pro_4_rsa` public key stored in it's deploy keys. That is how we authenticate the server and allow it to perform `git` read operations (fetch, pull).

When you have successfully completed & tested a feature, merge the feature branch into master. This will trigger a deploy in Codeship which will deploy the files to the production server. Once the updated files are on the server, you can run the relevant deploy script to deploy the updated plugin to users.

- wptouch-pro through beanstalk
    - deploys to ~/code/wptouch-pro-4/release

## Shell scripts for deploying the plugin to users

The following deploy scripts relate to the `wptouch` product but are found in the `wptouch-general` repo. These should be run *after* you have completed, QA tested, and deployed an update to the production server.

- `deploy/wp-touch-pro-4/`
    - `deploy-extras`
        - take whats on the server & package it up for all themes & extensions
        - push to amazon s3
        - update version numbers locally that the API uses
    - `deploy-free-plugin`
        - neuters the pro version
        - creates zip of free version
        - pushes up to amazon s3
        - then we download that created package
            - manually push it up to the WP subversion repo for release to free users
    - `deploy-languages`
        - push copies of lang files to s3 & creates files locally
        - API checks file size of that file and compares to customer to determine if it needs to download an update for them
    - `deploy-plugin`
        - main wpt4 deploy
        - create package
        - push to s3
        - update version # locally
        - within 15-20mins after running people see new version in wptouch admin panel
