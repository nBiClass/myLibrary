# myLibrary

#### 图片处理类

````

require "./vendor/autoload.php";
$img=__DIR__.'/1.png';  //要处理的图片

$obj = new \longzy\image($img);
$obj->text('10086',30,20,20,'#f28424');
$obj->show();

````