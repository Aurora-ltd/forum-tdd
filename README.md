
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
create dummy data for thread
```bash
    php artisan tinker
    Thread::factory(50)->create();
    $thread = Thread::factory(10)->create();
    $thread->each(function ($thread) { Reply::factory(10)->create(['thread_id' => $thread->id]); });
```