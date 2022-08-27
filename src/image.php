<?php
// +----------------------------------------------------------------------
// | 网站名称：萌新小猿
// +----------------------------------------------------------------------
// | 功能介绍：图片处理类，图片缩略，裁剪，水印，文字添加
// +----------------------------------------------------------------------
// | Created by PhpStorm.
// +----------------------------------------------------------------------
// | Time:  2021/08/14 10:00
// +----------------------------------------------------------------------
// | Author: 龙志勇 <longzhiyongdxfncel@163.com>
// +----------------------------------------------------------------------
// | Copyright  http://www.longzhiyong.com All rights reserved.
// +----------------------------------------------------------------------
// + ━━━━━━神兽出没━━━━━━
// +
// + 　　 ┏┓     ┏┓
// + 　　┏┛┻━━━━━┛┻┓
// + 　　┃　　　　　 ┃
// +　 　┃　　━　　　┃
// + 　　┃　┳┛　┗┳  ┃
// + 　　┃　　　　　 ┃
// + 　　┃　　┻　　　┃
// + 　　┃　　　　　 ┃
// + 　　┗━┓　　　┏━┛　Code is far away from bug with the animal protecting
// + 　　　 ┃　　　┃    神兽保佑,代码无bug
// + 　　　　┃　　　┃
// +　 　　　┃　　　┗━━━┓
// + 　　　　┃　　　　　　┣┓
// + 　　　　┃　　　　　　┏┛
// + 　　　　┗┓┓┏━┳┓┏┛
// + 　　　　 ┃┫┫ ┃┫┫
// + 　　　　 ┗┻┛ ┗┻┛
// +
// + ━━━━━━感觉萌萌哒━━━━━━
// +----------------------------------------------------------------------


namespace longzy;

class image {
    private $fontFile;  //字体文件决定路径
    private $mime;  //图形类型
    private $file;  //源文件路径
    private $img;   //图片资源
    private $mess;  //处理说明
    private $suffix;  //图片尾缀类型
    private $save;     //图片保存路径


    public function __construct($file = '') {
        if($file){
            $this->openFile($file);
        }

    }

    public function __destruct() {
        //销毁资源
        if ($this->img) {
            ImageDestroy($this->img);
        }
    }

    /*
     * 读取文件
     */
    public function openFile($file = ''){
        //图片是否存在
        $isFile = file_exists($file) && mb_strlen($file) > 4;
        if (!$isFile) {
            $this->img = null;
            $this->mess = '图片不存在';
            return false;
        }

        $this->file = $file;

        //图片 mime 验证
        $mime = mime_content_type($file);
        if (!in_array($mime, ['image/png', 'image/jpeg'])) {
            $this->mess = '目前仅支持PNG,JPG,JPEG格式图片处理';
            return false;
        }

        $this->mime = $mime;

        //图片尾缀验证
        $suffix = basename($file);
        $arr = explode('.', $suffix);
        $this->suffix = $suffix = strtolower($arr[1]);

        try {
            switch ($mime) {
                case 'image/png':
                    $this->img = imagecreatefrompng($file);
                    break;
                case 'image/jpeg':
                    $this->img = imagecreatefromjpeg($file);
                    break;
                default:
                    $this->mess = '目前仅支持PNG,JPG,JPEG格式图片处理';
                    return false;
            }


        } catch (\Exception $e) {
            $this->mess = $e->getMessage();
            return false;
        }
    }

    //获取提示信息
    public function getMessage() {
        return $this->mess;
    }

    public function setFontFile($filePath = '') {
        if (is_file($filePath)) {
            $this->fontFile = $filePath;
        }
    }

    /**
     * 保存图片
     * @param string $savePath 图片存储全路径路径, 例如：/hello.png
     * @param int $quality 图形质量，范围从 0（最差质量，文件更小）到 100（最佳质量，文件最大）
     * @return bool
     */
    public function save($savePath = '', $quality = false) {
        try {
            if (!$savePath && mb_strlen($savePath) <= 4) {
                $this->mess = '保存路径有误';
                return false;
            }
            $a = str_replace(basename($savePath), '', $savePath);
            $res = true;
            if (!file_exists($a)) {
                $res = mkdir($a, 0777, true);
            }
            if (!$res) {
                $this->mess = '保存路径创建失败，请确认是否有权限操作';
                return false;
            }

            if ($quality === false) {
                imagepng($this->img, $savePath);
            } else {
                imagejpeg($this->img, $savePath, $quality);
            }
            $this->mess = '图片保存成功';
            $this->save = $savePath;
            return true;
        } catch (\Exception $e) {
            $this->mess = $e->getMessage();
            return false;
        }
    }

    /**
     * 浏览器直接输出图片
     */
    public function show() {
        ob_clean();
        header('Content-Type:' . $this->mime);
        imagepng($this->img);
        imagedestroy($this->img);
        exit;
    }

    /**
     * 返回图片宽度
     * @return integer 图片宽度
     */
    public function width() {
        $size = getimagesize($this->file);
        return $size[0];
    }

    /**
     * 返回图片高度
     * @return integer 图片高度
     */
    public function height() {
        $size = getimagesize($this->file);
        return $size[1];
    }

    /**
     * 返回图像类型
     * @return string 图片类型
     */
    public function type() {
        $size = getimagesize($this->file);
        $arr = array(1 => 'GIF', 2 => 'JPG', 3 => 'PNG', 4 => 'SWF', 5 => 'PSD', 6 => 'BMP', 7 => 'TIFF(intel byte order)', 8 => 'TIFF(motorola byte order)', 9 => 'JPC', 10 => 'JP2', 11 => 'JPX', 12 => 'JB2', 13 => 'SWC', 14 => 'IFF', 15 => 'WBMP', 16 => 'XBM');
        $type = $size[2];
        return $arr[$type];
    }

    /**
     * 返回图像MIME类型
     * @return string 图像MIME类型
     */
    public function mime() {
        $size = getimagesize($this->file);
        return $size['mime'];
    }

    /**
     * 返回图像尺寸数组 0 - 图片宽度，1 - 图片高度
     * @return array 图片信息
     */
    public function size() {
        $size = getimagesize($this->file);
        return $size;
    }

    /**
     * 裁剪图片
     * @param integer $w 准备在原图上裁剪宽度
     * @param integer $h 准备在原图上裁剪高度
     * @param integer $x 准备从原图上那个x坐标开始裁剪
     * @param integer $y 准备从原图上那个y坐标开始裁剪
     * @param integer $width 图片保存宽度,不使用，保存为裁剪宽度
     * @param integer $height 图片保存高度,不使用，保存为裁剪高度
     */
    public function crop($w = 0, $h = 0, $x = 0, $y = 0, $width = null, $height = null) {
        try {
            $w = $w ? $w : $this->width();
            $h = $h ? $h : $this->height();


            $width = $width ? $width : $w;
            $height = $height ? $height : $h;
            //创建新图像
            $img = imagecreatetruecolor($width, $height);
            imagesavealpha($this->img, true); // 保留源图片透明度
            imagealphablending($img, false); // 不合并图片颜色
            imagesavealpha($img, true); // 保留目标图片透明度
            // 调整默认颜色
            $color = imagecolorallocate($img, 255, 255, 255);
            imagefill($img, 0, 0, $color);

            //裁剪
            imagecopyresampled($img, $this->img, 0, 0, $x, $y, $width, $height, $w, $h);
            imagedestroy($this->img); //销毁原图
            $this->img = $img;

            $this->mess = '图形裁剪成功';
            return false;
        } catch (\Exception $e) {
            $this->mess = $e->getMessage();
            return false;
        }
    }

    /**
     * 生成缩略图
     * @param integer $width 缩略图最大宽度
     * @param integer $height 缩略图最大高度
     * @param integer $type 缩略图裁剪类型 ；1|2|3|4, 1:等比例缩放、2：居中裁剪、3：左上角裁剪，4：右下角裁剪
     */
    public function thumb($width = 0, $height = 0, $type = 1) {
        try {
            //原图宽度和高度
            $w = $this->width();
            $h = $this->height();
            $height = $height ? $height : $width;

            //计算缩略图生成的必要参数
            switch ($type) {
                //居中裁剪
                case 2:
                    //设置缩略图的坐标及宽度和高度
                    $w = $width;
                    $h = $height;
                    $x = ($this->width() - $w) / 2;
                    $y = ($this->height() - $h) / 2;
                    break;

                //左上角裁剪
                case 3:
                    //设置缩略图的坐标及宽度和高度
                    $w = $width;
                    $h = $height;
                    $x = 0;
                    $y = 0;
                    break;

                // 右下角裁剪
                case 4:
                    //设置缩略图的坐标及宽度和高度
                    $x = $w - $width;
                    $y = $h - $height;
                    $w = $width;
                    $h = $height;
                    break;

                //默认等比缩放吧
                default:
                    //原图尺寸小于缩略图尺寸则不进行缩略
                    if ($w < $width && $h < $height) {
                        $x = $y = 0;
                        break;
                    }

                    //计算缩放比例
                    $scale = min($width / $w, $height / $h);

                    //设置缩略图的坐标及宽度和高度
                    $x = $y = 0;
                    $width = $w * $scale;
                    $height = $h * $scale;
                    break;
            }

            /* 裁剪图像 */
            $this->crop($w, $h, $x, $y, $width, $height);

            $this->mess = '图形压缩成功';
            return false;
        } catch (\Exception $e) {
            $this->mess = $e->getMessage();
            return false;
        }
    }


    /**
     * 添加水印
     * @param $source   水印图片路径
     * @param int $x 水印位置
     * @param int $y 水印位置
     * @param int $alpha 水印在图片中的透明度1-100
     * @return bool
     */
    public function water($source, $x = 0, $y = 0, $alpha = 50) {
        try {
            $alpha = $alpha < 0 ? 0 : $alpha;
            $alpha = $alpha > 100 ? 100 : $alpha;
            //资源检测
            if (empty($this->img)) {
                $this->mess = '没有可以被添加水印的图像资源';
                return false;
            }

            if (!is_file($source)) {
                $this->mess = '水印图像不存在';
                return false;
            }

            //获取水印图像信息
            $info = getimagesize($source);
            if (false === $info || empty($info['bits'])) {
                $this->mess = '非法水印文件';
                return false;
            }

            //图片 mime 验证
            $mime = mime_content_type($source);
            if (!in_array($mime, ['image/png', 'image/jpeg'])) {
                $this->mess = '水印文件格式目前仅支持PNG,JPG,JPEG';
                return false;
            }

            //创建水印图像资源
            $fun = 'imagecreatefrom' . image_type_to_extension($info[2], false);
            $water = $fun($source);

            //设定水印图像的混色模式
            imagealphablending($water, true);


            imagesavealpha($water, true); // 保留源图片透明度
            //        $img_target = ImageCreateTrueColor($width_save, $height_save);
            //        imagealphablending($img_target, false); // 不合并图片颜色

            //添加水印
            $src = imagecreatetruecolor($info[0], $info[1]);
            // 调整默认颜色
            //        $color = imagecolorallocate($src, 	238,130,238);
            //        imagefill($src, 0, 0, $color);

            imagecopy($src, $this->img, 0, 0, $x, $y, $info[0], $info[1]);
            imagecopy($src, $water, 0, 0, 0, 0, $info[0], $info[1]);
            imagecopymerge($this->img, $src, $x, $y, 0, 0, $info[0], $info[1], $alpha);

            //销毁临时图片资源
            imagedestroy($src);

            //销毁水印资源
            imagedestroy($water);

            $this->mess = '水印添加成功';
            return true;
        } catch (\Exception $e) {
            $this->mess = $e->getMessage();
            return false;
        }
    }


    /**
     * 图像添加文字
     * @param string $text 添加的文字
     * @param int $size 字体大小，默认16
     * @param int $x 文字写入位置 X 坐标，默认0
     * @param int $y 文字写入位置 Y 坐标，默认0,
     * @param string $color 文字颜色，默认#000000
     * @param int $angle 文字倾斜角度,1-180
     * @param int $offset 文字相对当前位置的偏移量
     * @param string $font
     * @return bool
     */
    public function text($text = '', $size = 16, $x = 0, $y = 0, $color = '#00000000', $angle = 0, $offset = 0, $font = __DIR__ . '/font.ttf') {
        try {
            $font = $this->fontFile ? $this->fontFile : $font;
            if (!is_file($font)) {
                $this->mess = '字体文件不存在';
                return false;
            }
            if (mb_strlen($text) <= 0) {
                $this->mess = '请输入水印文字';
                return false;
            }

            //获取文字信息
            $info = imagettfbbox($size, $angle, $font, $text);
            $minx = min($info[0], $info[2], $info[4], $info[6]);
            $maxx = max($info[0], $info[2], $info[4], $info[6]);
            $miny = min($info[1], $info[3], $info[5], $info[7]);
            $maxy = max($info[1], $info[3], $info[5], $info[7]);

            // 计算文字初始坐标和尺寸
            $x += $minx;
            $y += abs($miny);


            // 设置偏移量
            if (is_array($offset)) {
                $offset = array_map('intval', $offset);
                list($ox, $oy) = $offset;
            } else {
                $offset = intval($offset);
                $ox = $oy = $offset;
            }

            // 设置颜色
            if (is_string($color) && 0 === strpos($color, '#')) {
                $color = str_split(substr($color, 1), 2);
                $color = array_map('hexdec', $color);
                if (empty($color[3]) || $color[3] > 127) {
                    $color[3] = 0;
                }
            } elseif (!is_array($color)) {
                $this->mess = '错误的颜色值';
                return false;
            }

            $col = imagecolorallocatealpha($this->img, $color[0], $color[1], $color[2], $color[3]);
            imagettftext($this->img, $size, $angle, $x + $ox, $y + $oy, $col, $font, $text);

            $this->mess = '文字添加成功';
            return true;
        } catch (\Exception $e) {
            $this->mess = $e->getMessage();
            return false;
        }
    }

    /**
     * 制造水印图片
     * @param string $text 水印文字
     * @param false $save 保存路径，值为 false 浏览器直接输出
     * @param int $size 文字大小
     * @param int $angle 倾斜角度 1-90
     * @param string $color 文字颜色
     * @param string $font 字体文件
     * @return array|void
     */
    public function makeWeterImg($text = 'Hello Word', $save = false, int $size = 60, int $angle = 45, string $color = '#F8F8F8',$font = __DIR__ . '/font.ttf') {
        try {
            //计算字符串在图片中的长度
            $a = (imagettfbbox($size, 0, $font, $text));
            $length = $a[2] - $a[0];

            //根据输入文字计算出图片宽高，考虑因素，文字字体大小，倾斜角度。。应该是用三角函数算出最佳的宽高。我就随意了，算的脑瓜子疼
            $widh = abs(intval((($a[2] + $a[0]) * abs(cos(deg2rad($angle)))) + abs($a[7]) - $a[0]));
            $height = abs(intval($a[7])) + abs(intval(sin(deg2rad($angle)) * $length));

            //创建画布
            $image = ImageCreateTrueColor($widh, $height);
            $bg = imagecolorallocatealpha($image, 0, 0, 0, 127);      //设置背景完全透明
            imagefill($image, 0, 0, $bg);                                   //颜色填充
            imagesavealpha($image, true);                                   //保持透明度

            //文字颜色
            $color = str_split(substr($color, 1), 2);
            $color = array_map('hexdec', $color);
            if (empty($color[3]) || $color[3] > 127) {
                $color[3] = 0;
            }
            $col = imagecolorallocatealpha($image, $color[0], $color[1], $color[2], $color[3]);

            //文字坐标
            $x = abs($a[7] - $a[0]) * sin(deg2rad($angle));
            $y = $height;

            //写入文字
            imagettftext($image, $size, $angle, intval($x), intval($y), $col, $font, $text);

            //保存或者直接输出
            if ($save) {
                $savePath = str_replace(basename($save), '', $save);
                if (!file_exists($savePath)) {
                    mkdir($savePath, 0777, true);
                }
                imagepng($image, $save);
                imagedestroy($image);
                return array('code' => 200, 'msg' => '图片生成成功', ['path' => $save]);
            } else {
                header('Content-Type: image/png');
                imagepng($image);
                imagedestroy($image);
                exit;
            }
        } catch (\Exception $e) {
            return array('code' => 500, 'msg' => '系统错误', ['error' => $e->getMessage()]);
        }
    }

}