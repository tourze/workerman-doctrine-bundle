# Workerman Doctrine Bundle

[English](README.md) | [中文](README.zh-CN.md)

[![Latest Version](https://img.shields.io/packagist/v/tourze/workerman-doctrine-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/workerman-doctrine-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/workerman-doctrine-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/workerman-doctrine-bundle)

一个 Symfony Bundle，提供 Workerman 与 Doctrine ORM 的集成，确保在长期运行的进程中正确管理实体管理器的生命周期。

## 功能特性

- **实体管理器监控**: 自动监控 Workerman 进程中的 Doctrine EntityManager 状态
- **进程保护**: 当 EntityManager 关闭时自动停止 Workerman 进程，防止数据损坏
- **无缝集成**: 同时支持 HTTP 和控制台事件
- **零配置**: 开箱即用，无需复杂设置

## 安装

```bash
composer require tourze/workerman-doctrine-bundle
```

## 快速开始

1. 将 Bundle 添加到您的 `config/bundles.php`：

```php
<?php

return [
    // ... 其他 bundle
    Tourze\WorkermanDoctrineBundle\WorkermanDoctrineBundle::class => ['all' => true],
];
```

2. Bundle 将自动注册其服务和事件监听器。

## 工作原理

Bundle 包含一个 `EntityManagerWatchSubscriber` 来监控 Doctrine EntityManager 状态：

- **自动监控**: 监听 `KernelEvents::TERMINATE` 和 `ConsoleEvents::TERMINATE` 事件
- **进程安全**: 当 EntityManager 关闭时（通常由于异常），订阅者自动停止所有 Workerman 进程
- **干净关闭**: 通过确保进程使用新的 EntityManager 重启来防止数据损坏

## 使用场景

此 Bundle 特别适用于：

- 在长期运行的进程中使用 Workerman 运行 Symfony 应用程序
- 需要确保数据库连接稳定性
- 防止高并发场景中的 EntityManager 相关问题
- 构建健壮的长期运行 Web 服务

## 系统要求

- PHP 8.1 或更高版本
- Symfony 6.4 或更高版本
- Doctrine ORM 3.0 或更高版本
- Workerman 5.1 或更高版本
- ext-pcntl 扩展

## 贡献

详情请参阅 [CONTRIBUTING.md](CONTRIBUTING.md)。

## 许可证

MIT 许可证 (MIT)。更多信息请参阅 [License File](LICENSE)。