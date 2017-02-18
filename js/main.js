(function($) {
  'use strict'
  $(function () {

    // pathをSVGに流して描画してみる
    var svgView = function (path) {

            var svg = document.getElementById('res')
            var child
            // 今までのパスクリアする.
            while (child = svg.lastChild) {
              svg.removeChild(child);
            }


            var pElm = document.createElementNS('http://www.w3.org/2000/svg', 'path')
            pElm.setAttribute('d', path)
            console.log(pElm.getTotalLength())
            var g = document.createElementNS('http://www.w3.org/2000/svg', 'g')
            g.appendChild(pElm)
            svg.appendChild(g)
    }

    // canvasをトレース. pathを返す.
    var potrace = function (canvas) {
      var res = Potrace.trace(canvas)
      var path = res._outpath
      return path
    }

    $('#preview_btn').on('click', () => {
      var canvas = document.getElementById('render')
      var ctx = canvas.getContext('2d')
      // clear
      ctx.clearRect(0, 0, canvas.width, canvas.height)

      // 描画
      var title = $('#title_field').val()
      ctx.font = "40px 'ＭＳ Ｐゴシック'"
      ctx.fillStyle = 'blue';
      ctx.fillText(title, 0, 50, canvas.width * 0.8)

      // traceしてpathに落とす.
      var path = potrace(canvas)
      svgView(path)

      // フォームにパスをセット.
      $('#path_send_field').val(path);
    })
  })
})(jQuery)
