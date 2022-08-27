# myLibrary
> 算是在工作中积累的扩展类吧

#### 图片处理类

````
require "./vendor/autoload.php";
$img=__DIR__.'/1.png';  //要处理的图片

$obj = new \longzy\image($img);
//或者 
$obj = new \longzy\image();
$obj->openFile($img);

/*-------------------常用方法展示---------------------------------------*/

//添加水印文字
$obj->text('为中华之崛起而读书', 14, 20, 20, '#000000', 45);

//裁剪图片
$obj->crop(100, 100);

//缩略图片
$obj->thumb(100, 100);

//添加水印图片
$obj->water($img, '300', '100', 100);

//本地保存
$obj->save(__DIR__ . '/Hello.png', 100);

//浏览器输出
$obj->show();

//更多方法请打开类文件自行查看
````

#### 图片处理类-生成一张水印图片
````
//生成水印图片
$obj = new \longzy\image();
$obj->makeWeterImg('为中华之崛起而读书');
````