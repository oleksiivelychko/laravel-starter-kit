heroku-git:
	heroku git:remote -a oleksiivelychkolaravelboard

composer-install:
	composer install --no-scripts --ignore-platform-reqs
