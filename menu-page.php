<div class="content">
  <h1>サイトのトップヘッダにラインアニメーションをつける</h1>

  <h2>サイトタイトルを入力</h2>
  <div class="form">
    <label for="title_field">タイトル</label>
    <input id="title_field" type="text">
    <button id="preview_btn">SVG化</button>
  </div>

  <?php // これはトレースするためのcanvas  ?>
  <canvas id="render" width="300" height="100"></canvas>

  <h2>トップロゴ</h2>
  <svg id="res" fill="#ccc"
 viewBox="0 0 500 300" xmlns="http://www.w3.org/2000/svg">
    </svg>

    <form class=""  method="post">
      <input type="hidden" name="path" id="path_send_field">
      <button type="submit" name="button">保存する.</button>
    </form>
</div>
