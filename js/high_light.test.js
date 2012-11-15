function jsApi() {
   // {{{ function _hLight()

    /**
     * 按照分词数组的规则进行高亮关键字
     *
     * @param string str 待高亮字符串
     * @param string searchArr 分词
     * @return String 高亮后的字符串
     */
    this._hLight = function(str, searchArr) {
        var _tmpRegExp = [];
        // 组合高亮正则
        for (var _key in searchArr) {
            _tmpRegExp.push('(' + searchArr[_key] + ')');
        }

        // 正则替换
        var _regS = new RegExp(_tmpRegExp.join('|'), "gi");

        return str.replace(_regS, function(w) { return '<font style="color:#000;background:#ffff66;">' + w + '</font>'});
    }

    // }}}
}

var str = "this is a test sentence,这是一段测试语句";
var searchArr = ["this", "te", "测试"];
JSAPI = new jsApi();
JSAPI._hLight(str, searchArr);
