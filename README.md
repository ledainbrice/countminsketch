# countminsketch

Light Count Min Sketch implementation with storing on disk really fast.

## Installation via Composer
The recommended method to install is through [Composer](http://getcomposer.org).

1. Add `imoca/countminsketch` as a dependency in your project's `composer.json` file:

    ```json
        {
            "require": {
                "imoca/countminsketch": "*"
            }
        }
    ```

## API

```
    $cms = new Imoca\CountMinSketch\CountMinSketch($nb_hash, $nb_width, 'path_to_file_store');
    $cms->record("word_to_insert");
    $counter = $cms->count("word_to_count");

    echo $cms; //working
```
