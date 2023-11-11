<p align="center"><a href="https://autoklose.com" target="_blank"><img src="https://app.autoklose.com/images/svg/autoklose-logo-white.svg" width="400"></a></p>

## Instructions
The repository for the assignment is public and Github does not allow the creation of private forks for public repositories.

The correct way of creating a private fork by duplicating the repo is documented here.

For this assignment the commands are:

Create a bare clone of the repository.

git clone --bare git@github.com:autoklose/laravel-9.git
Create a new private repository on Github and name it laravel-9.

Mirror-push your bare clone to your new repository.

Replace <your_username> with your actual Github username in the url below.

cd laravel-9.git
git push --mirror git@github.com:<your_username>/laravel-9.git
Remove the temporary local repository you created in step 1.

cd ..
rm -rf laravel-9.git
You can now clone your laravel-9 repository on your machine (in my case in the code folder).

cd ~/code
git clone git@github.com:<your_username>/laravel-9.git


## Running the Application

### With Docker
To run the Application, you must install:
- **Docker** (https://www.docker.com/products/docker-desktop)

Then run this command in project root - This will install dependencies, migrate and also seed into the database
```console
$ make install
$ make up
```

You should be able to visit your app at http://laravel.test

Laravel Horizon is also available at http://laravel.test/horizon

### Environment
An `.env` is auto created from `.env.example` on project install you can change any of the values as needed.

### Token
Update `API_TOKEN` in `.env` to match the request param `api_token`

## Testing
To run tests:
```console
$ make run-tests
```

# Restart application
```console
$ make restart
```

# Re-Install Project
```console
$ make re-install
```

## Additional commands

```console
$ make down
$ make migrate
```
