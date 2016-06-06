Guenther
========

Günther is a command line tool that helps you building [Bolt](https://bolt.cm/) extensions. 

### Installation

Install *guenther* as global composer package:
```
composer global require "guenther/guenther"
```

### Features

Günther can:
- bootstrap new extensions
- create classes for service providers, listeners, controllers, etc...

### Usage

For all commands, it's required that you are in the root folder of your extension.

####  Bootstrap a New Extension:

```
cd extensions
mkdir -p yourname/extensionname
cd yourname/extensionname
guenther init yourname extensionname
```

### Create Classes

```
guenther make:listener StorageEventListener
```

Supported types:
- Controller
- Field
- Listener
- Command (nut)
- Provider

### Configuration

Guenther creates a `.guenther.yml` when you initialize a new extension. 
This file is used to define the sub folders and sub namespaces of the classes Günther can create.

The standard `.guenther.yml` looks like this:

```
controllers:
    folder: Controller
    namespace: Controller
fields:
    folder: Field
    namespace: Field
    template:
        folder: fields
providers:
    folder: Provider
    namespace: Provider
commands:
    folder: Nut
    namespace: Nut
listeners:
    folder: Listener
    namespace: Listener
```

**Note:** Folders and namespaces are relative to the extension `/src` folder and root namespace.

---

### License

This tool is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)