# Container
[![License](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)

## 🚀 Overview
A lightweight and simple PHP Dependency Injection (DI) container designed to help you manage your application's dependencies efficiently. This project is ideal for developers looking for a straightforward and easy-to-use DI container without the overhead of more complex solutions.

## ✨ Features
- 🔒 **Secure**: Built with security in mind, ensuring that your dependencies are resolved safely.
- 🌐 **Flexible**: Supports various types of definitions, including concrete, alias, and factory definitions.
- 🔄 **Reflection Autowiring**: Automatically resolves dependencies using reflection.
- 📦 **Lightweight**: Minimalistic design with a small footprint.

## 📦 Installation

### Prerequisites
- PHP 8.0 or later
- Composer

### Quick Start
```bash
# Clone the repository
git clone https://github.com/fugikzl/container.git

# Navigate to the project directory
cd container

# Install dependencies
composer install

# Run PSalm for static analysis
./vendor/bin/psalm --threads=4 --no-cache src
```

### Alternative Installation Methods
- **Composer**: You can add this container to your project using Composer.
  ```bash
  composer require fugikzl/container
  ```

## 🎯 Usage

### Basic Usage
```php
<?php

require 'vendor/autoload.php';

use Fugikzl\Container\Container;

$container = new Container();

$container->setConcrete('database', new PDO('mysql:host=localhost;dbname=test', 'user', 'pass'));
$database = $container->get('database');

echo $database->getAttribute(PDO::ATTR_CONNECTION_NAME);
```

### Advanced Usage
- **Factory Definition**:
  ```php
  use Fugikzl\Container\Internal\FactoryInterface;
  use Psr\Container\ContainerInterface;

  class LoggerFactory implements FactoryInterface
  {
    public function __invoke(ContainerInterface $container, ?string $requestedName = null): SomeLogger
    {
      return new SomeLogger(...);
    }
  }

  $container->setFactory('logger', LoggerFactory::class);
  $logger = $container->get('logger');
  ```

- **Alias Definition**:
  ```php
  $container->setAlias('db', 'database');
  $db = $container->get('db');
  ```

## 📁 Project Structure
```
container/
├── .gitignore
├── composer.json
├── Makefile
├── psalm.xml
└── src/
    ├── Container.php
    ├── Internal/
    │   ├── AliasDefinition.php
    │   ├── ConcreteDefinition.php
    │   ├── DefinitionInterface.php
    │   ├── Exception/
    │   │   └── UnableToResolveException.php
    │   ├── FactoryDefinition.php
    │   ├── FactoryInterface.php
    │   └── Undefined.php
    └── ReflectionFactory.php
```

## 🤝 Contributing
We welcome contributions! Here's how you can get involved:

### Development Setup
1. Clone the repository:
   ```bash
   git clone https://github.com/fugikzl/container.git
   cd container
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

3. Run PSalm for static analysis:
   ```bash
   ./vendor/bin/psalm --threads=4 --no-cache src
   ```

### Code Style Guidelines
- Follow PSR-12 coding standards.
- Use PHP 8.0 or later features.

## 📝 License
This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

This README is designed to be a comprehensive guide for developers looking to use and contribute to the `container` project. Happy coding! 🚀