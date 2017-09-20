# Laravel Deployer Skeleton
Our deployer skeleton for Laravel projects.

## Usage
Import the `deploy.php` file into your repository.

Replace the following values:
- `$APPLICATION_NAME$` The name of the application, used as a folder name on the server.
- `$GIT_REPO$` The Git repository that holds the project.
> Make sure to have a read only access key for the git repository!
- `$HOST$` The server ip.
- `$USER$` The user that we use to authenticate to the server.
- `$PRIVATE_KEY$` The private key to authenticate to the server.

### Credits
[Deployer](https://deployer.org/)
