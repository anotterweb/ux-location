current_user       := `id -u`
current_group      := `id -g`
current_user_group := current_user + ":" + current_group
current_dir        := invocation_directory()
composer_image     := "composer"
php_image          := "php:8.4-cli"

# Outputs this help screen
default:
  @just --list --unsorted

# Run composer command
composer *args:
  docker run -it --rm -u {{current_user_group}} -v "{{current_dir}}:/app" -w /app {{composer_image}} {{args}}

# Run php command
php *args:
  docker run -it --rm -u {{current_user_group}} -v "{{current_dir}}:/app" -w /app {{php_image}} php {{args}}

# Run tests
test *args:
  docker run -it --rm -u {{current_user_group}} -v "{{current_dir}}:/app" -w /app {{php_image}} vendor/bin/phpunit {{args}}