
# TDD with forum

practice TDD with build a forum


## Deployment

To deploy this project include the path in bashrc

```bash
  export PATH="~/.composer/vendor/bin:$PATH"
```
then run

```bash
  phpunit
```
without include bashrc file simply run

```bash
  ./vendor/bin/phpunit
  or
  ./vendor/bin/phpunit --filter CreateThreadsTest
```
