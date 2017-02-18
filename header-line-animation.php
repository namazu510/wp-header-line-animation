  <?php
  /*
  Plugin Name: Header-Line-Animation
  Plugin URI: https://namazu510.github.io/header-line-animation
  Description: HeaderLineDrawingAnimationPlugin
  Version: 1.0.0
  Author: Namazu510
  Licence: GPLv2 or later
  */

  new HeaderLineAnimation();
  class HeaderLineAnimation {

    // private $text_domain = 'wp-posted-display';
  	private $version     = '1.0.0';

    public function __construct () {
      add_action( 'admin_menu', array( $this, 'admin_menu' ));
      register_activation_hook( __FILE__, array( $this, 'create_tables' ));
      register_deactivation_hook( __FILE__, array( $this, 'delete_tables' ));

      // クライアントページ表示時にhock
      // add_action( 'write_title_svg', array( $this, 'write_svg'));
    }

    public function admin_menu () {
      $menu_page = add_menu_page(
          'HeaderLineDrawAnnimation', // ページタイトル
          'HeaderAnimation', //
          'manage_options', // 権限
          'header-line-draw-annimation', // スラッグ
          array( $this, 'menu_page_render' ), // コールバック関数
          'dashicons-admin-media' // アイコンクラス名 (optional)
        );

        // 管理画面用のスクリプト及びCSSを出力するようにアクション登録
        add_action( 'admin_print_scripts-' . $menu_page, array( $this, 'admin_scripts') );
  			add_action( 'admin_print_styles-' . $menu_page, array( $this, 'admin_styles') );
    }

    // 管理画面ページが表示されると呼ばれる.
    public function menu_page_render () {
      if ($_POST && $_POST['path']) {
        echo "保存したよ！";
        $this->add_path_data($_POST['path']);
      }
      // 管理画面ページレンダリング.
       include 'menu-page.php';
    }

    // svgを描画する処理等
    public function write_svg () {
      $path = $this->get_path_data();
      $svg =
      "<svg id='header-text'>
          <path d='$path'></path>
        </svg>";
      echo $svg;
    }

  	public function admin_styles () {
  		wp_enqueue_style( 'header-line-animation-admin-style', plugins_url( 'css/admin.css', __FILE__ ), array(), $this->version );
  	}

    // 管理画面用のスクリプト
    public function admin_scripts () {
      wp_enqueue_script( 'header-line-animation-potrace-js', plugins_url( 'js/potrace.js', __FILE__ ), array(), $this->version );
      wp_enqueue_script( 'header-line-animation-main-js', plugins_url( 'js/main.js', __FILE__ ), array('jquery'), $this->version );
    }

    // プラグイン用のテーブルを作る
    public function create_tables() {
      global $wpdb;

      $charset_collate = $wpdb->get_charset_collate();
      $table_name = $this->get_table_name();

      $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        path text NOT NULL,
        time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        UNIQUE KEY id (id)
      ) $charset_collate;";

      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
      dbDelta( $sql );
  }

  // プラグイン用のテーブル削除する.
  public function delete_tables () {
    global $wpdb;

    $table_name = $this->get_table_name();
    $sql = "DROP TABLE IF EXISTS $table_name";
    $wpdb->query($sql);
  }

  // プラグイン用のテーブル名を返す Util
  function get_table_name () {
    global $wpdb;
    return $wpdb->prefix . 'header_animation_plugin';
  }

  // svgのパスデータを登録する
  public function add_path_data ($path) {
    global $wpdb;
    $table_name = $this->get_table_name();
    $wpdb->insert(
        $table_name,
        array (
          'time' => current_time( 'mysql' ),
          'path' => $path,
        )
      );
  }

  // svgのパスデータとして最新のものを取得する
  public function get_path_data () {
    global $wpdb;
    $table_name = $this->get_table_name();
    $sql = "SELECT path FROM $table_name ORDER BY id DESC LIMIT 1";

    $res = $wpdb->get_var($sql);
    return $res;
  }
}
