# GLPI in Scalingo

This file will list actions performed to make the GLPI software work with a Scalingo deployment (in addition of the
history of commits)

## Install the database

Run the `db:install` command in a one-off container, then setup [security key][security-key]. Download the key via an
AWS
like storage:

1. Install `aws` without root access:

    ```shell
    curl "https://awscli.amazonaws.com/awscli-exe-linux-x86_64.zip" -o "awscliv2.zip"
    unzip awscliv2.zip
    ./aws/install -i ~/aws-cli -b ~/aws-cli/bin
    ```

1. Configure then put-object in a bucket

    ```shell
    ~/aws-cli/bin/aws configure
    ~/aws-cli/bin/aws s3api put-object --bucket <bucket> --key glpicrypt.key --body config/glpicrypt.key --endpoint <endpoint>
    ```

1. Download the key locally

Then set this file as an environment variable for your Scalingo project and write it back to a file
`./config/glpicrypt.key` when container starting as described [here][secret-file].

### Reasonning

Installing the database via the [installation wizard][install-wizard] is not working as it's taking more than 30
secondes and Scalingo has a 30 secondes hard limit for request before throwing a 504 HTTP error.

## Add Azure AD login

Use of open source plugin described on [this page][forum-sso]: `glpi-singlesignon`.

### Troubleshooting

- All files of plugin were copied to `plugins` folder, there might be a better way with composer?
  - After every installlation, GLPI deactivates all plugins, make sure to activate it back
- Be careful to properly set up Azure authentication web app
- Issue with PHP-FPM config around `security.limit_extensions`, fixed with custom Nginx config and other workarounds

[security-key]: https://glpi-install.readthedocs.io/en/latest/command-line.html#security-key

[secret-file]: https://doc.scalingo.com/platform/app/secret-file-in-app

[install-wizard]: https://glpi-install.readthedocs.io/en/latest/install/wizard.html

[forum-sso]: https://forum.glpi-project.org/viewtopic.php?id=285067
