# Kapusta
Simple PHP template engine.

Example:

```php
class Layout extends View
{
    public function __construct( public string $title ) {}


    public function render() { ?>
        <html>
        <head>
            <title><?= $this->title ?></title>
        </head>
        <body><?= $this->slot ?></body>
        </html>
    <?php }
}

class Page extends View
{

    public function render() { ?>
        <?php $layout = $this->begin(new Layout('title1')) ?>
            <?php $layout->title = 'title2'; ?>
            content
        <?php $layout->end(true) ?>
    <?php }
}

$page = new Page();
echo $page;
```

Output:
```html
<html>
    <head>
        <title>title2</title>
    </head>
    <body>content</body>
</html>
```


