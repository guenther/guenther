Guenther
========

Günther is a command line tool that helps you building [Bolt][bolt] extensions. 

## Installation

Install *guenther* as global composer package:
```
composer global require "guenther/guenther"
```

To use Günther by just running `guenter`, you will need to add the installation
directory to your `PATH` in your `~/.bash_profile` (or `~/.zshrc`) like this:

```
export PATH=~/.composer/vendor/bin:$PATH
```

## Features

Günther can:

 - bootstrap new extensions
 - create classes for service providers, listeners, controllers, etc…
 - show you quick descriptions and implementation snippets for several things

## Usage

For all commands, except `how:` ones, it's required that you are in the root folder of your
extension.

###  Bootstrap a New Extension:

```
cd extensions
mkdir -p local/yourname/extensionname
cd local/yourname/extensionname
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

### Built in Wiki

You can view descriptions and usage examples directly on the command line.

```
guenther how:listener
```

Supported types:
- Controller
- Field
- Listener
- Command (nut)
- Provider

### Validate an Extension

You can use the validate command to check if your extension is ready to be released. 
It will mostly check if all the meta data is on it's place, so you still have to 
check your extensions code and functionality on your own.

```
guenther validate
```

#### Force Command Execution

By default, this command will only run when it detects a local extension. 
However, you can force the execution with the `--force` or `-f` parameter.

```
guenther validate --force
```

#### Exit Codes

The validate command returns proper exit codes which makes it usable in a CI environment.

If all checks passed or even contain a few warnings, it will return with an exit code of `0`.
If there are any errors, it will return with an exit code of `1`.

#### Save Result to a File

You can output all the checks + the result to a file of your choice. 

##### Save as Text

To save the results table without the emojis to a file, use the following parameter:

```
guenther validate --output-text=/path/to/file.txt
```

##### Save as Json

To save a json string with all checks, their results and the final result, use the following parameter:

```
guenther validate --output-json=/path/to/file.json
```

##### Save as Yaml

To save a yaml string with all checks, their results and the final result, use the following parameter:

```
guenther validate --output-yml=/path/to/file.yml
```

##### All at Once

Of course, you can also use all three output options at once:

```
guenther validate --output-text=result.txt --output-json=result.json --output-yml=result.yml
```


## Configuration

Guenther creates a `.guenther.yml` when you initialize a new extension. This
file is used to define the sub folders and sub namespaces of the classes Günther
can create.

The default `.guenther.yml` looks like this:

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

**Note:** Folders and namespaces are relative to the extension `/src` folder and
root namespace.

---

## License

This tool is open-sourced software licensed under the [MIT license][mit].


[bolt]: https://bolt.cm/
[mit]: http://opensource.org/licenses/MIT
