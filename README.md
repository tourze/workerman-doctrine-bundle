# Workerman Doctrine Bundle

[English](README.md) | [中文](README.zh-CN.md)

[![Latest Version](https://img.shields.io/packagist/v/tourze/workerman-doctrine-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/workerman-doctrine-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/workerman-doctrine-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/workerman-doctrine-bundle)

A Symfony bundle that provides integration between Workerman and Doctrine ORM, ensuring proper entity manager lifecycle management in long-running processes.

## Features

- **Entity Manager Monitoring**: Automatically monitors Doctrine EntityManager state in Workerman processes
- **Process Protection**: Automatically stops Workerman processes when EntityManager is closed to prevent data corruption
- **Seamless Integration**: Works with both HTTP and Console events
- **Zero Configuration**: Works out of the box with minimal setup

## Installation

```bash
composer require tourze/workerman-doctrine-bundle
```

## Quick Start

1. Add the bundle to your `config/bundles.php`:

```php
<?php

return [
    // ... other bundles
    Tourze\WorkermanDoctrineBundle\WorkermanDoctrineBundle::class => ['all' => true],
];
```

2. The bundle will automatically register its services and event listeners.

## How It Works

The bundle includes an `EntityManagerWatchSubscriber` that monitors the Doctrine EntityManager state:

- **Automatic Monitoring**: Listens to `KernelEvents::TERMINATE` and `ConsoleEvents::TERMINATE` events
- **Process Safety**: When the EntityManager is closed (usually due to an exception), the subscriber automatically stops all Workerman processes
- **Clean Shutdown**: Prevents data corruption by ensuring processes restart with a fresh EntityManager

## Use Cases

This bundle is particularly useful when:

- Running Symfony applications with Workerman in long-running processes
- Needing to ensure database connection stability
- Preventing EntityManager-related issues in high-concurrency scenarios
- Building robust long-running web services

## Requirements

- PHP 8.1 or higher
- Symfony 6.4 or higher
- Doctrine ORM 3.0 or higher
- Workerman 5.1 or higher
- ext-pcntl extension

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.