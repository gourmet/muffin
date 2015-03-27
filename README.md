# Muffin

[![Total Downloads](https://poser.pugx.org/gourmet/muffin/downloads.svg)](https://packagist.org/packages/gourmet/muffin)
[![License](https://poser.pugx.org/gourmet/muffin/license.svg)](https://packagist.org/packages/gourmet/muffin)

[FactoryMuffin] for [CakePHP 3].

## About

Out of the box FactoryMuffin wraps [Faker] methods to generate dummy data for your entities.

I originally started by releasing [gourmet/faker], which worked great for inserting dummy data 
to use in demos and even in tests. However, as time went by, I realized that in many cases, I 
was just repeating myself. That's when I remembered [@savant] mentioning [FactoryMuffin] to me 
a while back and after a quick look, it became obvious this was exactly what I needed.

I still use the [Faker][gourmet/faker] plugin, because in some cases just absracting everything 
using factories ends up being even more work. So, if you need granularity when creating dummy data, 
try it; but in most cases, stick to this one.

## Usage

```
composer require --dev gourmet/muffin:dev-master
```

No need to load it in `bootstrap.php`.

## Example

Assuming you have a `Posts` model (table, entity and fixture), to create fake data, you'll first
need to define the factory in `tests/Factory/PostFactory`:

```php
<?php
namespace App\Test\Factory;

use Gourmet\Muffin\TestSuite\TestFactory;

class PostFactory extends TestFactory
{
    public $title = 'sentence|5';  // a sentence with 5 words
    public $body = 'text';         // some text
    public $author = 'name';       // a person's name
}
```

Which you can then use in your tests like so:

```php
public function setUp()
{
    $this->FeedFactory = new \App\Test\Factory\PostFactory();
}

public function testSomething()
{
    $post = $this->FeedFactory(1); // create a single record
    $this->assertTrue(isset($post->id));

    $this->FeedFactory(10); // create 10 records
}
```

For more information on the available methods for creating dummy data and how to use them, check the
[FactoryMuffin](http://factory-muffin.thephpleague.com) and [Faker] docs.

## License

Copyright (c)2015, Jad Bitar and licensed under [The MIT License][mit].

[FactoryMuffin]:https://github.com/thephpleague/factory-muffin
[CakePHP 3]:https://cakephp.org
[@savant]:http://github.com/savant
[Faker]:/fzaninotto/faker
[mit]:http://www.opensource.org/licenses/mit-license.php
[gourmet/faker]:http://github.com/gourmet/faker