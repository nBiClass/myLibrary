<?php
// +----------------------------------------------------------------------
// | 网站名称：
// +----------------------------------------------------------------------
// | 功能介绍：
// +----------------------------------------------------------------------
// | Time:  2022/1/15
// +----------------------------------------------------------------------
// | Copyright  www.XXXXX.com All rights reserved.
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

/**
 * 加密解密类
 * 该算法仅支持加密数字。比较适用于数据库中id字段的加密解密，以及根据数字显示url的加密。
 * @加密原则 标记长度 + 补位 + 数字替换
 * @加密步骤：
 * 将a-z,A-Z,0-9 62个字符打乱，取前M(数字最大的位数)位作为 标记长度字符串，取第M+1 到第M+10位为数字替换字符串，剩余的为补位字符串
 * 1.计算数字长度n,取乱码的第n位作为标记长度。
 * 2.计算补位的长度，加密串的长度N -1 - n 为补位的长度。根据指定的算法得到补位字符串。
 * 3.根据数字替换字符串替换数字，得到数字加密字符串。
 * 标记长度字符 + 补位字符串 + 数字加密字符串 = 加密串
 */

namespace longzy;
class xdecode {
    private $strbase = "FgeWcAXQEKwTd54nRtZOSyJizUP6EB1u3Mxb8mNlpvf70CsakVjqHDhoG2YLrI";
    private $strbaseLower = "df1l2e9qgzxuio4m0w5hjk6vb7rty8cn3pas";
    private $key, $length, $codelen, $codenums, $codeext;

    /**
     * @param int $length 标记长度字符
     * @param float $key 数字加密字符串
     * @param false $lower 是否使用纯小写和数字，或者定义的打乱顺序的不重复字符串【全部26位字母+10位数字 或者 52位大小写字母+10位数字】
     */
    function __construct($key = 3.145902678, $lower = false) {
        if ($lower === true) {
            $this->strbase = $this->strbaseLower;
        } elseif ($lower) {
            $this->strbase = $lower;
        }

        $length = intval(mb_strlen($this->strbase) / 3);

        $this->key = $key;
        $this->length = $length;
        $this->codelen = substr($this->strbase, 0, $this->length);
        $this->codenums = substr($this->strbase, $this->length, 10);
        $this->codeext = substr($this->strbase, $this->length + 10);
    }

    // 编码
    function encode($nums) {
        $rtn = "";
        $numslen = strlen($nums);
        //密文第一位标记数字的长度
        $begin = substr($this->codelen, $numslen - 1, 1);
        //密文的扩展位
        $extlen = $this->length - $numslen - 1;
        $temp = str_replace('.', '', $nums / $this->key);
        $temp = substr($temp, -$extlen);
        $arrextTemp = str_split($this->codeext);
        $arrext = str_split($temp);
        foreach ($arrext as $v) {
            $rtn .= $arrextTemp[$v];
        }
        $arrnumsTemp = str_split($this->codenums);
        $arrnums = str_split($nums);
        foreach ($arrnums as $v) {
            $rtn .= $arrnumsTemp[$v];
        }
        return $begin . $rtn;
    }

    //解码
    function decode($code) {
        $rtn = '';
        if ($code == '') {
            return $rtn;
        }
        $begin = substr($code, 0, 1);
        $len = strpos($this->codelen, $begin);
        if ($len !== false) {
            $len++;
            $arrnums = str_split(substr($code, -$len));
            foreach ($arrnums as $v) {
                $rtn .= strpos($this->codenums, $v);
            }
        }

        return $rtn;
    }
}