//http://gist.github.com/66579 & altered
// & http://github.com/mojombo/clippy
 (function($) {
    $.fn.clippy = function() {
        return this.each(function() {
            var $this = $(this);
            var text = $this.text() || $this.val();

            var bgcolor = ($this.css('background-color') == 'transparent') ? '#fff': $this.css('background-color');

            var node = $this;
            while (node.css('background-color') == 'transparent' && node.length > 1) {
                node = node.parent();
            }
            if (node.length == 1) {
                bgcolor = '#ffffff';
            } else {
                bgcolor = node.css('background-color');
            }
            var m = bgcolor.match(/^rgb\(\s*(\d+),\s*(\d+)\s*,\s*(\d+)\s*\)$/i)
                if (m) {
                var r = parseInt(m[1], 10),
                g = parseInt(m[2], 10),
                b = parseInt(m[3], 10);
                bgcolor = '#' + r.toString(16) + g.toString(16) + b.toString(16);
            }

            $this
                .after($('<span class="clippy"><object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="110" height="14" class="clippy"> <param name="movie" value="flash/clippy.swf"/> <param name="allowScriptAccess" value="always" /> <param name="quality" value="high" /> <param name="scale" value="noscale" /> <param name="wmode" value="transparent" /> <param NAME="FlashVars" value="text=' + escape(text) + '> <param name="bgcolor" value="' + bgcolor + '"> <embed src="flash/clippy.swf" width="110" height="14" wmode="transparent" name="clippy" quality="high" allowScriptAccess="always" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" FlashVars="text=' + escape(text) + '" bgcolor="' + bgcolor + '" /> </object></span>'))
                .after('&nbsp;');
        });
    };
})
(jQuery);