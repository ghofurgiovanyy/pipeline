# Pipelines, Supercharged!

<p align="center"><img src="https://raw.githubusercontent.com/GhofurGiovany/pipeline/master/example.png" width="900" alt="Example code showcasing the Pipeline package using the with transaction method and the pipable trait"></p>

## Installation

Install via composer:

```bash
composer require GhofurGiovany/pipeline
```

## Sending pipes down the pipeline

When configuring the pipeline, you can send an array of class strings, invokable objects, closures, objects with a `handle()` method, or any other type that passes `is_callable()`.

```php
use GhofurGiovany\Pipeline\Pipeline;

class RegisterController
{
    public function store(StoreRegistrationRequest $request)
    {
        return Pipeline::make()
            ->send($request->all())
            ->through([
                RegisterUser::class,
                AddMemberToTeam::class,
                SendWelcomeEmail::class,
            ])
            ->then(fn ($data) => UserResource::make($data));
    }
}
```

Another approach you can take is to implement this as a trait on a data object. (You could even implement it on your `FormRequest` object if you really wanted.)

```php
use GhofurGiovany\Pipeline\Pipable;

class UserDataObject
{
    use Pipable;

    public string $name;
    public string $email;
    public string $password;
    // ...
}

class RegisterController
{
    public function store(StoreRegistrationRequest $request)
    {
        return UserDataObject::fromRequest($request)
            ->pipeThrough([
                RegisterUser::class,
                AddMemberToTeam::class,
                SendWelcomeEmail::class,
            ])
            ->then(fn ($data) => UserResource::make($data));
    }

    // you also can pipe the request

    return $request->pipe()
            ->withTransaction()
            ->through([
                RegisterUser::class,
                AddMemberToTeam::class,
                SendWelcomeEmail::class,
            ])
            ->then(fn ($data) => UserResource::make($data));
}
```

To maintain compatibility with Laravel's `Pipeline` class, the `through()` method can accept either a single array of callables or multiple parameters, where each parameter is one of the callable types listed previously. However, the `pipeThrough()` trait method only accepts an array, since it also has a second optional parameter.

## Using database transactions

When you want to use database transactions in your pipeline, the method will be different depending on if you're using the trait or the `Pipeline` class.

Using the `Pipeline` class:

```php
Pipeline::make()->withTransaction()
```

The `withTransaction()` method will tell the pipeline to use transactions. When you call the `then()` or `thenReturn()` methods, a database transaction will begin before executing the pipes. If an exception is encountered during the pipeline, the transaction will be rolled back so no data is committed to the database. Assuming the pipeline completed successfully, the transaction is committed.

When using the trait, you can pass a second parameter to the `pipeThrough()` method:

```php
$object->pipeThrough($pipes, withTransaction: true);
```

## Testing

```bash
composer test
```
