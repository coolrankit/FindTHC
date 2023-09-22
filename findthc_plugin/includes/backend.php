<?php
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Test_List extends WP_List_Table {

	private static $table = 'thcv_tests';
	private static $base_request = 'admin.php?page=testo';
	public function __construct() {
		parent::__construct( array(
			'singular' => __( 'Listing Test', 'RegulusReign' ),
			'plural'   => __( 'Listing Tests', 'RegulusReign' ),
			'screen' => isset( $args['screen'] ) ? $args['screen'] : null,
			'ajax'     => true
		) );
	}

	function get_columns() {
		$columns = array(
			'cb'      => '<input type="checkbox" />',
			'item' => __( 'Item', 'RegulusReign' ),
			'dispensary' => __( 'Dispensary', 'RegulusReign' ),
			'strain' => __( 'Strain', 'RegulusReign' ),
			'lab' => __( 'Laboratory', 'RegulusReign' ),
			'status' => __( 'Status', 'RegulusReign' ),
			'result' => __( "Result", 'RegulusReign' ),
		);
		return $columns;
	}
	public function get_sortable_columns() {
		$sortable_columns = array(
			'item' => array( 'pTitle', false ),
			'dispensary' => array( 'dTitle', false ),
			'strain' => array( 'sTitle', false ),
			'lab' => array( 'lTitle', false ),
			'status' => array( 'status', false ),
		);
		return $sortable_columns;
	}
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'item':
				return $item['pTitle'];
			case 'dispensary':
				return '<a href="'.get_permalink($item['dID']).'">'.$item['dTitle'].'</a>';
			case 'strain':
				return '<a href="'.get_permalink($item['strain']).'">'.$item['sTitle'].'</a>';
			case 'lab':
				return '<a href="'.get_permalink($item['lab']).'">'.$item['lTitle'].'</a>';
			case 'status':
				return (($item['status'])? 'Approved':'Unapproved');
			case 'result':
				$rs = array();
				if($item[THC]){$rs[] = 'THC:'.$item[THC].'%';}
				if($item[CBD]){$rs[] = 'CBD:'.$item[CBD].'%';}
				if($item[CBN]){$rs[] = 'CBN:'.$item[CBN].'%';}
				if($item[CBG]){$rs[] = 'CBG:'.$item[CBG].'%';}
				if($item[CBC]){$rs[] = 'CBC:'.$item[CBC].'%';}
				if($item[Limonene]){$rs[] = 'Limonene:'.$item[Limonene].'%';}
				if($item[Myrcene]){$rs[] = 'Myrcene:'.$item[Myrcene].'%';}
				if($item[Pinene]){$rs[] = 'Pinene:'.$item[Pinene].'%';}
				if($item[Linalool]){$rs[] = 'Linalool:'.$item[Linalool].'%';}
				if($item[BCaryophyllene]){$rs[] = 'B-Caryophyllene:'.$item[BCaryophyllene].'%';}
				if($item[Nerolidol]){$rs[] = 'Nerolidol:'.$item[Nerolidol].'%';}
				if($item[Phytol]){$rs[] = 'Phytol:'.$item[Phytol].'%';}
				if($item[Cineol]){$rs[] = 'Cineol:'.$item[Cineol].'%';}
				if($item[Humulene]){$rs[] = 'Humulene:'.$item[Humulene].'%';}
				if($item[Borneol]){$rs[] = 'Borneol:'.$item[Borneol].'%';}
				if($item[Terpinolene]){$rs[] = 'Terpinolene:'.$item[Terpinolene].'%';}
				return ((!empty($rs))? implode(', ', $rs):'-');
			default:
				return (($item[$column_name])? $item[$column_name]:'');
		}
	}
	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="bulk-ids[]" value="%s" />', $item['ID']
		);
	}

	public static function get_results( $per_page = 10, $page_number = 1 ) {

		global $wpdb; $table = $wpdb->prefix . self::$table;
		$uid = get_current_user_id();

		$sql = "SELECT * FROM `$table` WHERE `type`='testo'".((current_user_can('administrator'))? "":" AND `lAuthor`='$uid'");
		
		if(isset($_REQUEST['status'])){
			$sql .= " AND `status`='".esc_sql($_REQUEST['status'])."'";
		}

		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$orderby = $_REQUEST['orderby'];
			$sql .= ' ORDER BY ' . esc_sql( $orderby );
			$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
		}

		$sql .= " LIMIT $per_page";
		$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;

		$result = $wpdb->get_results( $sql, 'ARRAY_A' );

		return $result;
	}
	public static function approve_item($id) {
		$data = array('ID'=>$id, 'status'=>1);
		update_thct_test($data);
	}
	public static function unapprove_item($id){
		$data = array('ID'=>$id, 'status'=>0);
		update_thct_test($data);
	}
	public static function delete_item($id){
		delete_thct_test($id, 'ID');
	}
	public static function record_count($pid='', $pd='') {
		global $wpdb; $table = $wpdb->prefix . self::$table;
		$uid = get_current_user_id();

		$sql = "SELECT COUNT(*) FROM `$table` WHERE `type`='testo'".((current_user_can('administrator'))? "":" AND `lAuthor`='$uid'");
		
		if($pid!='' && $pd!=''){
			$sql .= " AND `$pid`='$pd'";
		}

		return $wpdb->get_var( $sql );
	}
	
	public function get_bulk_actions() {
		$actions = array(
			'bulk-approve' => 'Approve',
			'bulk-unapprove' => 'Unapprove',
			'bulk-delete' => 'Delete',
		);

		return $actions;
	}
	public function process_bulk_action() {
		$nonce = $_REQUEST['_wpnonce'];
		if ( (isset( $_POST['action'] ) && $_POST['action'] == 'bulk-approve') || (isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-approve') ) {
			if ( isset($_REQUEST['_wpnonce']) && !wp_verify_nonce($nonce, 'bulk-' . $this->_args['plural']) ) {die( 'Go get a life script kiddies' );}
			else {
				$ids = esc_sql( $_POST['bulk-ids'] );
				foreach ($ids as $id) {
				self::approve_item($id);
				}
			}
		}
		if ( (isset( $_POST['action'] ) && $_POST['action'] == 'bulk-unapprove') || (isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-unapprove') ) {
			if ( isset($_REQUEST['_wpnonce']) && !wp_verify_nonce($nonce, 'bulk-' . $this->_args['plural']) ) {die( 'Go get a life script kiddies' );}
			else {
				$ids = esc_sql( $_POST['bulk-ids'] );
				foreach ($ids as $id) {
				self::unapprove_item($id);
				}
			}
		}
		if ( (isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete') || (isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete') ) {
			if ( isset($_REQUEST['_wpnonce']) && !wp_verify_nonce($nonce, 'bulk-' . $this->_args['plural']) ) {die( 'Go get a life script kiddies' );}
			else {
				$ids = esc_sql( $_POST['bulk-ids'] );
				foreach ($ids as $id) {
				self::delete_item($id);
				}
			}
		}
	}

	public function get_views(){
		if(self::is_base_request()){$class = 'class="current"';} else {$class = '';}
		$status_links['all'] = "<a href='edit.php?post_type=lab&page=testo' $class>".'All <span class="count">('.self::record_count().')</span></a>';

		if(self::is_base_request('status', '1')){$class = 'class="current"';} else {$class = '';}
		$status_links['approved'] = "<a href='edit.php?post_type=lab&page=testo&status=1' $class>".'Approved <span class="count">('.self::record_count('status', '1').')</span></a>';

		if(self::is_base_request('status', '0')){$class = 'class="current"';} else {$class = '';}
		$status_links['unapproved'] = "<a href='edit.php?post_type=lab&page=testo&status=0' $class>".'Unapproved <span class="count">('.self::record_count('status', '0').')</span></a>';

		return $status_links;
	}
	public function views() {
		$views = self::get_views();
		$views = apply_filters( "views_{$this->screen->id}", $views );
		if ( empty( $views ) ) {
			return;
		} else {
			echo "<ul class='subsubsub'>\n";
			foreach ( $views as $class => $view ) {
	
				$views[ $class ] = "\t<li class='$class'>$view";
			}
			echo implode( " |</li>\n", $views ) . "</li>\n";
			echo "</ul>";
		}
	}
	public function prepare_items() {
	
		$this->_column_headers = $this->get_column_info();

		$this->process_bulk_action();

		$per_page     = $this->get_items_per_page( 'items_per_page', 10 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();

		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'per_page'    => $per_page
		) );

		$this->items = self::get_results( $per_page, $current_page );
	}
	public function no_items() {
		_e( 'No listing test result available.', 'RegulusReign' );
	}
	protected function is_base_request($pid='', $pd='') {
		$i = 0;
		if(!empty($_GET['page'])){$i++;}
		if(!empty($_GET['order'])){$i++;}
		if(!empty($_GET['orderby'])){$i++;}
		if(!empty($_GET['post_type'])){$i++;}
		if (empty($_GET)) {
			return false;
		} elseif (count($_GET)==$i && !$pid && !$pd) {
			return true;
		} elseif (count($_GET)>$i && $pid!='' && $pd!='' && $_GET[$pid]==$pd) {
			return true;
		} else {
			return false;
		}
	}
	public function single_row( $item ) {
		echo '<tr id="item-'.$item[ID].'" class="items-row">';
		$this->single_row_columns( $item );
		echo '</tr>';
	}
	public function display(){
	?>
	<?php
			self::views();
			parent::display();
	}

}
/************************************************************************************************************************************************/

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Jurn_List extends WP_List_Table {

	private static $table = 'thcv_tests';
	private static $base_request = 'admin.php?page=jurno';
	public function __construct() {
		parent::__construct( array(
			'singular' => __( 'Journal Chemotype', 'RegulusReign' ),
			'plural'   => __( 'Journal Chemotypes', 'RegulusReign' ),
			'screen' => isset( $args['screen'] ) ? $args['screen'] : null,
			'ajax'     => true
		) );
	}

	function get_columns() {
		$columns = array(
			'cb'      => '<input type="checkbox" />',
			'item' => __( 'Item', 'RegulusReign' ),
			'strain' => __( 'Strain', 'RegulusReign' ),
			'lab' => __( 'Laboratory', 'RegulusReign' ),
			'status' => __( 'Status', 'RegulusReign' ),
			'result' => __( "Result", 'RegulusReign' ),
		);
		return $columns;
	}
	public function get_sortable_columns() {
		$sortable_columns = array(
			'item' => array( 'pTitle', false ),
			'strain' => array( 'sTitle', false ),
			'lab' => array( 'lTitle', false ),
			'status' => array( 'status', false ),
		);
		return $sortable_columns;
	}
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'item':
				return $item['pTitle'];
			case 'strain':
				return '<a href="'.get_permalink($item['strain']).'">'.$item['sTitle'].'</a>';
			case 'lab':
				return '<a href="'.get_permalink($item['lab']).'">'.$item['lTitle'].'</a>';
			case 'status':
				return (($item['status'])? 'Approved':'Unapproved');
			case 'result':
				$rs = array();
				if($item[THC]){$rs[] = 'THC:'.$item[THC].'%';}
				if($item[CBD]){$rs[] = 'CBD:'.$item[CBD].'%';}
				if($item[CBN]){$rs[] = 'CBN:'.$item[CBN].'%';}
				if($item[CBG]){$rs[] = 'CBG:'.$item[CBG].'%';}
				if($item[CBC]){$rs[] = 'CBC:'.$item[CBC].'%';}
				if($item[Limonene]){$rs[] = 'Limonene:'.$item[Limonene].'%';}
				if($item[Myrcene]){$rs[] = 'Myrcene:'.$item[Myrcene].'%';}
				if($item[Pinene]){$rs[] = 'Pinene:'.$item[Pinene].'%';}
				if($item[Linalool]){$rs[] = 'Linalool:'.$item[Linalool].'%';}
				if($item[BCaryophyllene]){$rs[] = 'B-Caryophyllene:'.$item[BCaryophyllene].'%';}
				if($item[Nerolidol]){$rs[] = 'Nerolidol:'.$item[Nerolidol].'%';}
				if($item[Phytol]){$rs[] = 'Phytol:'.$item[Phytol].'%';}
				if($item[Cineol]){$rs[] = 'Cineol:'.$item[Cineol].'%';}
				if($item[Humulene]){$rs[] = 'Humulene:'.$item[Humulene].'%';}
				if($item[Borneol]){$rs[] = 'Borneol:'.$item[Borneol].'%';}
				if($item[Terpinolene]){$rs[] = 'Terpinolene:'.$item[Terpinolene].'%';}
				return ((!empty($rs))? implode(', ', $rs):'-');
			default:
				return (($item[$column_name])? $item[$column_name]:'');
		}
	}
	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="bulk-ids[]" value="%s" />', $item['ID']
		);
	}

	public static function get_results( $per_page = 10, $page_number = 1 ) {

		global $wpdb; $table = $wpdb->prefix . self::$table;
		$uid = get_current_user_id();

		$sql = "SELECT * FROM `$table` WHERE `type`='jurno'".((current_user_can('administrator'))? "":" AND `lAuthor`='$uid'");
		
		if(isset($_REQUEST['status'])){
			$sql .= " AND `status`='".esc_sql($_REQUEST['status'])."'";
		}

		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$orderby = $_REQUEST['orderby'];
			$sql .= ' ORDER BY ' . esc_sql( $orderby );
			$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
		}

		$sql .= " LIMIT $per_page";
		$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;

		$result = $wpdb->get_results( $sql, 'ARRAY_A' );

		return $result;
	}
	public static function approve_item($id) {
		$data = array('ID'=>$id, 'status'=>1);
		update_thct_test($data);
	}
	public static function unapprove_item($id){
		$data = array('ID'=>$id, 'status'=>0);
		update_thct_test($data);
	}
	public static function delete_item($id){
		delete_thct_test($id, 'ID');
	}
	public static function record_count($pid='', $pd='') {
		global $wpdb; $table = $wpdb->prefix . self::$table;
		$uid = get_current_user_id();

		$sql = "SELECT COUNT(*) FROM `$table` WHERE `type`='jurno'".((current_user_can('administrator'))? "":" AND `lAuthor`='$uid'");
		
		if($pid!='' && $pd!=''){
			$sql .= " AND `$pid`='$pd'";
		}

		return $wpdb->get_var( $sql );
	}
	
	public function get_bulk_actions() {
		$actions = array(
			'bulk-approve' => 'Approve',
			'bulk-unapprove' => 'Unapprove',
			'bulk-delete' => 'Delete',
		);

		return $actions;
	}
	public function process_bulk_action() {
		$nonce = $_REQUEST['_wpnonce'];
		if ( (isset( $_POST['action'] ) && $_POST['action'] == 'bulk-approve') || (isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-approve') ) {
			if ( isset($_REQUEST['_wpnonce']) && !wp_verify_nonce($nonce, 'bulk-' . $this->_args['plural']) ) {die( 'Go get a life script kiddies' );}
			else {
				$ids = esc_sql( $_POST['bulk-ids'] );
				foreach ($ids as $id) {
				self::approve_item($id);
				}
			}
		}
		if ( (isset( $_POST['action'] ) && $_POST['action'] == 'bulk-unapprove') || (isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-unapprove') ) {
			if ( isset($_REQUEST['_wpnonce']) && !wp_verify_nonce($nonce, 'bulk-' . $this->_args['plural']) ) {die( 'Go get a life script kiddies' );}
			else {
				$ids = esc_sql( $_POST['bulk-ids'] );
				foreach ($ids as $id) {
				self::unapprove_item($id);
				}
			}
		}
		if ( (isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete') || (isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete') ) {
			if ( isset($_REQUEST['_wpnonce']) && !wp_verify_nonce($nonce, 'bulk-' . $this->_args['plural']) ) {die( 'Go get a life script kiddies' );}
			else {
				$ids = esc_sql( $_POST['bulk-ids'] );
				foreach ($ids as $id) {
				self::delete_item($id);
				}
			}
		}
	}

	public function get_views(){
		if(self::is_base_request()){$class = 'class="current"';} else {$class = '';}
		$status_links['all'] = "<a href='edit.php?post_type=lab&page=jurno' $class>".'All <span class="count">('.self::record_count().')</span></a>';

		if(self::is_base_request('status', '1')){$class = 'class="current"';} else {$class = '';}
		$status_links['approved'] = "<a href='edit.php?post_type=lab&page=jurno&status=1' $class>".'Approved <span class="count">('.self::record_count('status', '1').')</span></a>';

		if(self::is_base_request('status', '0')){$class = 'class="current"';} else {$class = '';}
		$status_links['unapproved'] = "<a href='edit.php?post_type=lab&page=jurno&status=0' $class>".'Unapproved <span class="count">('.self::record_count('status', '0').')</span></a>';

		return $status_links;
	}
	public function views() {
		$views = self::get_views();
		$views = apply_filters( "views_{$this->screen->id}", $views );
		if ( empty( $views ) ) {
			return;
		} else {
			echo "<ul class='subsubsub'>\n";
			foreach ( $views as $class => $view ) {
	
				$views[ $class ] = "\t<li class='$class'>$view";
			}
			echo implode( " |</li>\n", $views ) . "</li>\n";
			echo "</ul>";
		}
	}
	public function prepare_items() {
	
		$this->_column_headers = $this->get_column_info();

		$this->process_bulk_action();

		$per_page     = $this->get_items_per_page( 'items_per_page', 10 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();

		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'per_page'    => $per_page
		) );

		$this->items = self::get_results( $per_page, $current_page );
	}
	public function no_items() {
		_e( 'No journal chemotype available.', 'RegulusReign' );
	}
	protected function is_base_request($pid='', $pd='') {
		$i = 0;
		if(!empty($_GET['page'])){$i++;}
		if(!empty($_GET['order'])){$i++;}
		if(!empty($_GET['orderby'])){$i++;}
		if(!empty($_GET['post_type'])){$i++;}
		if (empty($_GET)) {
			return false;
		} elseif (count($_GET)==$i && !$pid && !$pd) {
			return true;
		} elseif (count($_GET)>$i && $pid!='' && $pd!='' && $_GET[$pid]==$pd) {
			return true;
		} else {
			return false;
		}
	}
	public function single_row( $item ) {
		echo '<tr id="item-'.$item[ID].'" class="items-row">';
		$this->single_row_columns( $item );
		echo '</tr>';
	}
	public function display(){
	?>
	<?php
			self::views();
			parent::display();
	}

}
/************************************************************************************************************************************************/

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Chem_List extends WP_List_Table {

	private static $table = 'thcv_tests';
	private static $base_request = 'admin.php?page=chemo';
	public function __construct() {
		parent::__construct( array(
			'singular' => __( 'Strain Chemotype', 'RegulusReign' ),
			'plural'   => __( 'Strain Chemotypes', 'RegulusReign' ),
			'screen' => isset( $args['screen'] ) ? $args['screen'] : null,
			'ajax'     => true
		) );
	}

	function get_columns() {
		$columns = array(
			'cb'      => '<input type="checkbox" />',
			'item'    => __( 'Item', 'RegulusReign' ),
			'strain' => __( 'Strain', 'RegulusReign' ),
			'lab' => __( 'Laboratory', 'RegulusReign' ),
			'status' => __( 'Status', 'RegulusReign' ),
			'result' => __( "Result", 'RegulusReign' ),
		);
		return $columns;
	}
	public function get_sortable_columns() {
		$sortable_columns = array(
			'lab' => array( 'lTitle', false ),
			'strain' => array( 'sTitle', false ),
			'status' => array( 'status', false ),
		);
		return $sortable_columns;
	}
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'item':
				return 'Phenotype ID#'.$item['phenID'];
			case 'strain':
				return '<a href="'.get_permalink($item['strain']).'">'.$item['sTitle'].'</a>';
			case 'lab':
				return '<a href="'.get_permalink($item['lab']).'">'.$item['lTitle'].'</a>';
			case 'status':
				return (($item[$column_name])? 'Approved':'Unapproved');
			case 'result':
				$rs = array();
				if($item[THC]){$rs[] = 'THC:'.$item[THC].'%';}
				if($item[CBD]){$rs[] = 'CBD:'.$item[CBD].'%';}
				if($item[CBN]){$rs[] = 'CBN:'.$item[CBN].'%';}
				if($item[CBG]){$rs[] = 'CBG:'.$item[CBG].'%';}
				if($item[CBC]){$rs[] = 'CBC:'.$item[CBC].'%';}
				if($item[Limonene]){$rs[] = 'Limonene:'.$item[Limonene].'%';}
				if($item[Myrcene]){$rs[] = 'Myrcene:'.$item[Myrcene].'%';}
				if($item[Pinene]){$rs[] = 'Pinene:'.$item[Pinene].'%';}
				if($item[Linalool]){$rs[] = 'Linalool:'.$item[Linalool].'%';}
				if($item[BCaryophyllene]){$rs[] = 'B-Caryophyllene:'.$item[BCaryophyllene].'%';}
				if($item[Nerolidol]){$rs[] = 'Nerolidol:'.$item[Nerolidol].'%';}
				if($item[Phytol]){$rs[] = 'Phytol:'.$item[Phytol].'%';}
				if($item[Cineol]){$rs[] = 'Cineol:'.$item[Cineol].'%';}
				if($item[Humulene]){$rs[] = 'Humulene:'.$item[Humulene].'%';}
				if($item[Borneol]){$rs[] = 'Borneol:'.$item[Borneol].'%';}
				if($item[Terpinolene]){$rs[] = 'Terpinolene:'.$item[Terpinolene].'%';}
				return ((!empty($rs))? implode(', ', $rs):'-');
			default:
				return (($item[$column_name])? $item[$column_name]:'');
		}
	}
	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="bulk-ids[]" value="%s" />', $item['ID']
		);
	}

	public static function get_results( $per_page = 10, $page_number = 1 ) {

		global $wpdb; $table = $wpdb->prefix . self::$table;
		$uid = get_current_user_id();

		$sql = "SELECT * FROM `$table` WHERE `type`='chemo'".((current_user_can('administrator'))? "":" AND `lAuthor`='$uid'");
		
		if(isset( $_REQUEST['status'] )){
			$sql .= " AND `status`='".esc_sql($_REQUEST['status'])."'";
		}

		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$orderby = $_REQUEST['orderby'];
			$sql .= ' ORDER BY ' . esc_sql( $orderby );
			$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
		}

		$sql .= " LIMIT $per_page";
		$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;

		$result = $wpdb->get_results( $sql, 'ARRAY_A' );

		return $result;
	}
	public static function approve_item($id) {
		$data = array('ID'=>$id, 'status'=>1);
		update_thct_test($data);
	}
	public static function unapprove_item($id){
		$data = array('ID'=>$id, 'status'=>0);
		update_thct_test($data);
	}
	public static function delete_item($id){
		delete_thct_test($id, 'ID');
	}
	public static function record_count($pid='', $pd='') {
		global $wpdb; $table = $wpdb->prefix . self::$table;
		$uid = get_current_user_id();

		$sql = "SELECT COUNT(*) FROM `$table` WHERE `type`='chemo'".((current_user_can('administrator'))? "":" AND `lAuthor`='$uid'");
		
		if($pid!='' && $pd!=''){
			$sql .= " AND `$pid`='$pd'";
		}

		return $wpdb->get_var( $sql );
	}
	
	public function get_bulk_actions() {
		$actions = array(
			'bulk-approve' => 'Approve',
			'bulk-unapprove' => 'Unapprove',
			'bulk-delete' => 'Delete',
		);

		return $actions;
	}
	public function process_bulk_action() {
		$nonce = $_REQUEST['_wpnonce'];
		if ( (isset( $_POST['action'] ) && $_POST['action'] == 'bulk-approve') || (isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-approve') ) {
			if ( isset($_REQUEST['_wpnonce']) && !wp_verify_nonce($nonce, 'bulk-' . $this->_args['plural']) ) {die( 'Go get a life script kiddies' );}
			else {
				$ids = esc_sql( $_POST['bulk-ids'] );
				foreach ($ids as $id) {
				self::approve_item($id);
				}
			}
		}
		if ( (isset( $_POST['action'] ) && $_POST['action'] == 'bulk-unapprove') || (isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-unapprove') ) {
			if ( isset($_REQUEST['_wpnonce']) && !wp_verify_nonce($nonce, 'bulk-' . $this->_args['plural']) ) {die( 'Go get a life script kiddies' );}
			else {
				$ids = esc_sql( $_POST['bulk-ids'] );
				foreach ($ids as $id) {
				self::unapprove_item($id);
				}
			}
		}
		if ( (isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete') || (isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete') ) {
			if ( isset($_REQUEST['_wpnonce']) && !wp_verify_nonce($nonce, 'bulk-' . $this->_args['plural']) ) {die( 'Go get a life script kiddies' );}
			else {
				$ids = esc_sql( $_POST['bulk-ids'] );
				foreach ($ids as $id) {
				self::delete_item($id);
				}
			}
		}
	}

	public function get_views(){
		if(self::is_base_request()){$class = 'class="current"';} else {$class = '';}
		$status_links['all'] = "<a href='edit.php?post_type=lab&page=chemo' $class>".'All <span class="count">('.self::record_count().')</span></a>';

		if(self::is_base_request('status', '1')){$class = 'class="current"';} else {$class = '';}
		$status_links['approved'] = "<a href='edit.php?post_type=lab&page=chemo&status=1' $class>".'Approved <span class="count">('.self::record_count('status', '1').')</span></a>';

		if(self::is_base_request('status', '0')){$class = 'class="current"';} else {$class = '';}
		$status_links['unapproved'] = "<a href='edit.php?post_type=lab&page=chemo&status=0' $class>".'Unapproved <span class="count">('.self::record_count('status', '0').')</span></a>';

		return $status_links;
	}
	public function views() {
		$views = self::get_views();
		$views = apply_filters( "views_{$this->screen->id}", $views );
		if ( empty( $views ) ) {
			return;
		} else {
			echo "<ul class='subsubsub'>\n";
			foreach ( $views as $class => $view ) {
	
				$views[ $class ] = "\t<li class='$class'>$view";
			}
			echo implode( " |</li>\n", $views ) . "</li>\n";
			echo "</ul>";
		}
	}
	public function prepare_items() {
	
		$this->_column_headers = $this->get_column_info();

		$this->process_bulk_action();

		$per_page     = $this->get_items_per_page( 'items_per_page', 10 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();

		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'per_page'    => $per_page
		) );

		$this->items = self::get_results( $per_page, $current_page );
	}
	public function no_items() {
		_e( 'No strain chemotype available.', 'RegulusReign' );
	}
	protected function is_base_request($pid='', $pd='') {
		$i = 0;
		if(!empty($_GET['page'])){$i++;}
		if(!empty($_GET['order'])){$i++;}
		if(!empty($_GET['orderby'])){$i++;}
		if(!empty($_GET['post_type'])){$i++;}
		if (empty($_GET)) {
			return false;
		} elseif (count($_GET)==$i && !$pid && !$pd) {
			return true;
		} elseif (count($_GET)>$i && $pid!='' && $pd!='' && $_GET[$pid]==$pd) {
			return true;
		} else {
			return false;
		}
	}
	public function single_row( $item ) {
		echo '<tr id="item-'.$item[ID].'" class="items-row">';
		$this->single_row_columns( $item );
		echo '</tr>';
	}
	public function display(){
	?>
	<?php
			self::views();
			parent::display();
	}
}
/************************************************************************************************************************************************/

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Sug_List extends WP_List_Table {

	private static $table = 'thct_suggestions';
	private static $base_request = 'admin.php?page=sugs';
	public function __construct() {
		parent::__construct( array(
			'singular' => __( 'Suggestions', 'RegulusReign' ),
			'plural'   => __( 'Suggestions', 'RegulusReign' ),
			'screen'   => isset( $args['screen'] ) ? $args['screen'] : null,
			'ajax'     => true
		) );
	}

	function get_columns() {
		$columns = array(
			'cb'      => '<input type="checkbox" />',
			'item'  => __( 'Strain', 'RegulusReign' ),
			'dtype'     => __( 'Type', 'RegulusReign' ),
			'details'  => __( 'Details', 'RegulusReign' ),
		);
		return $columns;
	}
	public function column_default( $item, $column_name ) {
		$ka = get_option('all_phentypes');
		switch ( $column_name ) {
			case 'item':
				return '<a href="'.get_permalink($item[$column_name]).'">'.get_the_title($item[$column_name]).'</a>';
			case 'dtype':
				//if($item[$column_name]=='ac'){return "Chemotype";}
				if($item[$column_name]=='sp'){return "Phenotype";}
				//if($item[$column_name]=='gj'){return "Grow Journal";}
				if($item[$column_name]=='fg'){return "Grow Data";}
			case 'details':
				$data = unserialize($item[data]);
				if($item[dtype]=='sp'){
				$r = 'Ratio: '.$data[ratio].'<br>';
				if($ka){foreach($ka as $k=>$v){
					$rs[] = $v.': '.$data[$k];
				}}
				return ((!empty($rs))? $r.implode(', ', $rs):'-');
				}
				if($item[dtype]=='fg'){
					$w = 'Indoor Yield in: '.$data[inyield].', Outdoor Yield in: '.$data[outyield].', Indoor Flowering Time: '.$data[inflower].', Outdoor Harvest Time: '.$data[outhervest];
					$x = $data[award]; $v = '';
					if($x[rank] && $x[type]){$v = $x[rank].' in '.$x[type];}
					elseif($x[rank]){$v = $x[rank];}
					if($x[rank] && $x[name] && $x[year]){$v .= ' - '.$x[name].' - '.$x[year];}
					elseif($x[rank] && $x[name]){$v .= ' - '.$x[name];}
					return $w.', Award: '.$v;

				}
				/*if($item[dtype]=='ac'){
				}*/
			default:
				return (($item[$column_name])? $item[$column_name]:'');
		}
	}
	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="bulk-ids[]" value="%s" />', $item['ID']
		);
	}

	public static function get_results( $per_page = 10, $page_number = 1 ) {


		global $wpdb; $table = $wpdb->prefix . self::$table;
		$uid = get_current_user_id();
		if(current_user_can('administrator')){$sql = "SELECT * FROM `$table` WHERE `ID`>0";} else{$sql = "SELECT * FROM `$table` WHERE `owner`='$uid'";}
		
		if(! empty( $_REQUEST['dtype'] )){
			$sql .= " AND `dtype`='".esc_sql($_REQUEST['dtype'])."'";
		}
		/*if(! empty( $_REQUEST['status'] )){
			$sql .= " AND `itype`='".esc_sql($_REQUEST['status'])."'";
		} else {$sql .= " AND `itype`='strain'";}*/

		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$orderby = $_REQUEST['orderby'];
			$sql .= ' ORDER BY ' . esc_sql( $orderby );
			$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
		}

		$sql .= " LIMIT $per_page";
		$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;

		$result = $wpdb->get_results( $sql, 'ARRAY_A' );

		return $result;
	}
	public static function approve_item($id) {
		global $wpdb; $table = $wpdb->prefix . self::$table;
		$sql = "SELECT * FROM `$table` WHERE `ID`='$id'";
		$r = $wpdb->get_row($sql, ARRAY_A);
		if($r[dtype]=='fg'){
			$dts = get_all_data($r[item]);
			$dts = array_merge($dts, unserialize($r[data]));
			update_post_meta($r[item], '_all_datas', $dts);
			foreach($dts as $k=>$v){
				if(($k=='father' || $k=='mother') && $v=='0'){update_post_meta($r[item], $k, $v);}
				else{update_post_meta($r[item], $k, $v);}
			}
		}
		if($r[dtype]=='sp'){
			$sts = get_thct_phenotypes($r[item]);
			$nst = unserialize($r[data]);
			if(count($sts)<6){addEdit_thct_phenotype($nst);}
		}
		self::delete_item($id);
	}
	public static function delete_item($id){
		global $wpdb; $table = $wpdb->prefix . self::$table;
		$where = array('ID'=>$id);
		$wpdb->delete($table, $where);
	}
	public static function record_count($pid='', $pd='') {
		global $wpdb; $table = $wpdb->prefix . self::$table;
		$uid = get_current_user_id();


		if(current_user_can('administrator')){$sql = "SELECT COUNT(*) FROM `$table` WHERE `ID`>0";} else{$sql = "SELECT COUNT(*) FROM `$table` WHERE `owner`='$uid'";}
		
		if(!empty($pid) && !empty($pd)){
			$sql .= " AND `$pid`='$pd'";
		}

		return $wpdb->get_var( $sql );
	}
	
	public function get_bulk_actions() {
		$actions = array(
			'bulk-approve' => 'Approve',
			'bulk-delete' => 'Delete',
		);

		return $actions;
	}
	public function process_bulk_action() {
		$nonce = $_REQUEST['_wpnonce'];
		if ( (isset( $_POST['action'] ) && $_POST['action'] == 'bulk-approve') || (isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-approve') ) {
			if ( isset($_REQUEST['_wpnonce']) && !wp_verify_nonce($nonce, 'bulk-' . $this->_args['plural']) ) {die( 'Go get a life script kiddies' );}
			else {
				$ids = esc_sql($_POST['bulk-ids']);
				foreach ($ids as $id) {
				self::approve_item($id);
				}
			}
		}
		if ( (isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete') || (isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete') ) {
			if ( isset($_REQUEST['_wpnonce']) && !wp_verify_nonce($nonce, 'bulk-' . $this->_args['plural']) ) {die( 'Go get a life script kiddies' );}
			else {
				$ids = esc_sql($_POST['bulk-ids']);
				foreach ($ids as $id) {
				self::delete_item($id);
				}
			}
		}
	}

	public function get_views(){
		if(self::is_base_request()){$class = 'class="current"';} else {$class = '';}
		$status_links['all'] = "<a href='admin.php?page=sugs' $class>".'All <span class="count">('.self::record_count().')</span></a>';

		if(self::is_base_request('dtype', 'fg')){$class = 'class="current"';} else {$class = '';}
		$status_links['fg'] = "<a href='admin.php?page=sugs&dtype=fg' $class>".'Grow Data <span class="count">('.self::record_count('dtype', 'fg').')</span></a>';

		if(self::is_base_request('dtype', 'sp')){$class = 'class="current"';} else {$class = '';}
		$status_links['sp'] = "<a href='admin.php?page=sugs&dtype=sp' $class>".'Phenotypes <span class="count">('.self::record_count('dtype', 'sp').')</span></a>';

		return $status_links;
	}
	public function views() {
		$views = self::get_views();
		$views = apply_filters( "views_{$this->screen->id}", $views );
		if ( empty( $views ) ) {
			return;
		} else {
			echo "<ul class='subsubsub'>\n";
			foreach ( $views as $class => $view ) {
	
				$views[ $class ] = "\t<li class='$class'>$view";
			}
			echo implode( " |</li>\n", $views ) . "</li>\n";
			echo "</ul>";
		}
	}
	public function prepare_items() {
	
		$this->_column_headers = $this->get_column_info();

		$this->process_bulk_action();

		$per_page     = $this->get_items_per_page( 'items_per_page', 10 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();

		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'per_page'    => $per_page
		) );

		$this->items = self::get_results( $per_page, $current_page );
	}
	public function no_items() {
		_e( 'No suggestion available.', 'RegulusReign' );
	}
	protected function is_base_request($pid='', $pd='') {
		if (empty($_GET)) {
			return false;
		} elseif (count($_GET)>=1 && $_GET['page']=="sugs" && !$pid && !$pd && !$_GET['dtype']) {
			return true;
		} elseif (count($_GET)>=2 && $_GET['page']=="sugs" && $pid && $pd && $_GET[$pid]==$pd) {
			return true;
			return false;
		}

	}
	public function single_row( $item ) {
		echo '<tr id="item-'.$item[ID].'" class="items-row">';
		$this->single_row_columns( $item );
		echo '</tr>';
	}
	public function display(){
			self::views();
			parent::display();
	}
}
/************************************************************************************************************************************************/

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Clm_List extends WP_List_Table {

	private static $table = 'thct_claims';
	private static $base_request = 'admin.php?page=clms';
	public function __construct() {
		parent::__construct( array(
			'singular' => __( 'Claimed List', 'RegulusReign' ),
			'plural'   => __( 'Claimed List', 'RegulusReign' ),
			'screen'   => isset( $args['screen'] ) ? $args['screen'] : null,
			'ajax'     => true
		) );
	}

	function get_columns() {
		$columns = array(
			'cb'      => '<input type="checkbox" />',
			'post'  => __( 'Item', 'RegulusReign' ),
			'email'  => __( 'Email', 'RegulusReign' ),
			'reps'     => __( 'Rep.ID', 'RegulusReign' ),
			'dated'  => __( 'Date', 'RegulusReign' ),
		);
		return $columns;
	}
	public function get_sortable_columns() {
		$sortable_columns = array(
			'post' => array( 'post', false ),
			'reps' => array( 'reps', false ),
			'dated' => array( 'dated', false ),
		);
		return $sortable_columns;
	}
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'post':
				return '<a href="'.get_permalink($item[$column_name]).'">'.get_the_title($item[$column_name]).'</a>';
			default:
				return (($item[$column_name])? $item[$column_name]:'');
		}
	}
	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="bulk-ids[]" value="%s" />', $item['ID']
		);
	}

	public static function get_results( $per_page = 10, $page_number = 1 ) {


		global $wpdb; $table = $wpdb->prefix . self::$table;

		$sql = "SELECT * FROM `$table` WHERE `ID`>'0'";
		
		if(! empty( $_REQUEST['status'] )){
			$sql .= " AND `status`='".esc_sql($_REQUEST['status'])."'";
		} else {$sql .= " AND `status`='0'";}


		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$orderby = $_REQUEST['orderby'];
			$sql .= ' ORDER BY ' . esc_sql( $orderby );
			$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
		}

		$sql .= " LIMIT $per_page";
		$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;

		$result = $wpdb->get_results( $sql, 'ARRAY_A' );

		return $result;
	}
	public static function approve_item($id) {
		global $wpdb; $table = $wpdb->prefix . self::$table;
		$sql = "SELECT * FROM `$table` WHERE `ID`='$id'";
		$clm = $wpdb->get_row($sql, ARRAY_A);

		if(is_email($clm[email])){
			$ee = email_exists($clm[email]);
			$ir = (is_integer($clm[reps]) && $clm[reps]>0 && user_can($clm[reps], 'rep'));
			if($ee){
				$p = get_post($clm[post]);
				$u = new WP_User($ee);
				if($p->post_type=='dispensary'){$u->add_role('dispensary'); $u->remove_role('subscriber'); setClaimIDs($clm[post], $ee, 'dispensary'); if($ir){update_post_meta($clm[post], 'representative', $clm[reps]);}}
				//if($p->post_type=='clinic'){$u->add_role('doctor');}
				//if($p->post_type=='lab'){$u->add_role('lab');}
				thcClaimMail($clm);
				$clm[status] = 1; addEditClaim($clm);
			} else {
				$ee = register_new_user($clm[email], $clm[email]);
				if(!is_wp_error($ee)){
				$p = get_post($clm[post]);
				$u = new WP_User($ee);
				if($p->post_type=='dispensary'){$u->add_role('dispensary'); $u->remove_role('subscriber'); setClaimIDs($clm[post], $ee, 'dispensary'); if($ir){update_post_meta($clm[post], 'representative', $clm[reps]);}}
				//if($p->post_type=='clinic'){$u->add_role('doctor');}
				//if($p->post_type=='lab'){$u->add_role('lab');}
				thcClaimMail($clm);
				$clm[status] = 1; addEditClaim($clm);
				} else {self::delete_item($id);}
			}
		} else {self::delete_item($id);}
	}
	public static function delete_item($id){
		global $wpdb; $table = $wpdb->prefix . self::$table;
		$where = array('ID'=>$id);
		$wpdb->delete($table, $where);
	}
	public static function record_count($pid='', $pd='') {
		global $wpdb; $table = $wpdb->prefix . self::$table;

		$sql = "SELECT * FROM `$table` WHERE `ID`>0";
		
		if(!empty($pid) && !empty($pd)){
			$sql .= " AND `$pid`='$pd'";
		}

		return $wpdb->get_var( $sql );
	}
	
	public function get_bulk_actions() {
		$actions = array(
			'bulk-approve' => 'Approve',
			'bulk-delete' => 'Delete',
		);
		if($_REQUEST['status']==1){unset($actions['bulk-approve']);}
		return $actions;
	}
	public function process_bulk_action() {
		$nonce = $_REQUEST['_wpnonce'];
		if ( (isset( $_POST['action'] ) && $_POST['action'] == 'bulk-approve') || (isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-approve') ) {
			if ( isset($_REQUEST['_wpnonce']) && !wp_verify_nonce($nonce, 'bulk-' . $this->_args['plural']) ) {die( 'Go get a life script kiddies' );}
			else {
				$ids = esc_sql( $_POST['bulk-ids'] );
				foreach ($ids as $id) {
				self::approve_item($id);
				}
			}
		}
		if ( (isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete') || (isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete') ) {
			if ( isset($_REQUEST['_wpnonce']) && !wp_verify_nonce($nonce, 'bulk-' . $this->_args['plural']) ) {die( 'Go get a life script kiddies' );}
			else {
				$ids = esc_sql( $_POST['bulk-ids'] );
				foreach ($ids as $id) {
				self::delete_item($id);
				}
			}
		}
	}

	public function get_views(){
		if(self::is_base_request()){$class = 'class="current"';} else {$class = '';}
		$status_links['unapp'] = "<a href='admin.php?page=clms' $class>".'Unapproved <span class="count">('.self::record_count('status', 0).')</span></a>';

		if(self::is_base_request('status', 1)){$class = 'class="current"';} else {$class = '';}
		$status_links['appr'] = "<a href='admin.php?page=clms&status=1' $class>".'Approved <span class="count">('.self::record_count('status', '1').')</span></a>';

		return $status_links;
	}
	public function views() {
		$views = self::get_views();
		$views = apply_filters( "views_{$this->screen->id}", $views );
		if ( empty( $views ) ) {
			return;
		} else {
			echo "<ul class='subsubsub'>\n";
			foreach ( $views as $class => $view ) {
	
				$views[ $class ] = "\t<li class='$class'>$view";
			}
			echo implode( " |</li>\n", $views ) . "</li>\n";
			echo "</ul>";
		}
	}
	public function prepare_items() {
	
		$this->_column_headers = $this->get_column_info();

		$this->process_bulk_action();

		$per_page     = $this->get_items_per_page( 'items_per_page', 10 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();

		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'per_page'    => $per_page
		) );

		$this->items = self::get_results( $per_page, $current_page );
	}
	public function no_items() {
		_e( 'No suggestion available.', 'RegulusReign' );
	}
	protected function is_base_request($pid='', $pd='') {
		if (empty($_GET)) {
			return false;
		} elseif (count($_GET)==2 && !empty($_GET['page']) && !$pid && !$pd) {
			return true;
		} elseif (count($_GET)==3 && !empty($_GET['page']) && !$pid && !$pd && ($_GET['order'] || $_GET['orderby'])) {
			return true;
		} elseif (count($_GET)==4 && !empty($_GET['page']) && !$pid && !$pd && ($_GET['order'] && $_GET['orderby'])) {
			return true;
		} elseif (count($_GET)>=1 && !empty($_GET['page']) && $pid && !$pd && !empty($_GET[$pid])) {
			return true;
		} elseif (count($_GET)>=1 && !empty($_GET['page']) && $pid && $pd && $_GET[$pid]==$pd) {
			return true;
		} else {
			return false;
		}
	}
	public function single_row( $item ) {
		echo '<tr id="item-'.$item[ID].'" class="items-row">';
		$this->single_row_columns( $item );
		echo '</tr>';
	}
	public function display(){
	?>
	<?php
			self::views();
			parent::display();
	}
}

/************************************************************************************************************************************************/

class AdminList {

	static $instance;
	static $test_obj;
	static $jurn_obj;
	static $chem_obj;
	static $sug_obj;
	static $clm_obj;

	public function __construct() {
		add_filter( 'set-screen-option', array( __CLASS__, 'set_screen' ), 10, 3 );
		add_action( 'admin_menu', array( $this, 'plugin_menu' ) );
	}
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
	public static function set_screen( $status, $option, $value ) {
		return $value;
	}
	public function screen_option() {

		$option = 'per_page';
		$args   = array(
			'label'   => 'Number of items per page:',
			'default' => 10,
			'option'  => 'items_per_page'
		);

		add_screen_option( $option, $args );
	}
	public function screen_option1(){$this->screen_option(); $this->test_obj = new Test_List();}
	public function screen_option2(){$this->screen_option(); $this->chem_obj = new Chem_List();}
	public function screen_option3(){$this->screen_option(); $this->sug_obj = new Sug_List();}
	public function screen_option4(){$this->screen_option(); $this->clm_obj = new Clm_List();}
	public function screen_option5(){$this->screen_option(); $this->jurn_obj = new Jurn_List();}

	public function plugin_menu() {
		$hook1 = add_submenu_page('edit.php?post_type=lab', 'Listing Tests', 'Listing Tests', 'edit_labs', 'testo', array($this, 'tastes_page'));
		add_action( "load-$hook1", array( $this, 'screen_option1' ) );

		$hook5 = add_submenu_page('edit.php?post_type=lab', 'Journal Chemotypes', 'Journal Chemotypes', 'edit_labs', 'jurno', array($this, 'tastes_page3'));
		add_action( "load-$hook5", array( $this, 'screen_option5' ) );

		$hook2 = add_submenu_page('edit.php?post_type=lab', 'Strain Chemotypes', 'Strain Chemotypes', 'edit_labs', 'chemo', array($this, 'tastes_page2'));
		add_action( "load-$hook2", array( $this, 'screen_option2' ) );

		$hook3 = add_menu_page('Suggetions', 'Suggetions', 'edit_strains', 'sugs', array($this, 'sugs_page'));
		add_action( "load-$hook3", array( $this, 'screen_option3' ) );

		$hook4 = add_menu_page('Claimed List', 'Claimed List', 'administrator', 'clms', array($this, 'clms_page'));
		add_action( "load-$hook4", array( $this, 'screen_option4' ) );
	}

	function tastes_page() {
		?>
		<div class="wrap">
			<h1>Strain Test Results</h1>

			<?php $this->test_obj->prepare_items();?>
			<form method="post">
			<?php $this->test_obj->display(); ?>
			</form>
		</div>
	<?php
	}
	public function tastes_page2() {
		?>
		<div class="wrap">
			<h1>Strain Chemotypes</h1>

			<?php $this->chem_obj->prepare_items();?>
			<form method="post">
			<?php $this->chem_obj->display(); ?>
			</form>
		</div>
	<?php
	}
	public function tastes_page3() {
		?>
		<div class="wrap">
			<h1>Journal Chemotypes</h1>

			<?php $this->jurn_obj->prepare_items();?>
			<form method="post">
			<?php $this->jurn_obj->display(); ?>
			</form>
		</div>
	<?php
	}
	public function sugs_page() {
		?>
		<div class="wrap">
			<h1>My Suggetions</h1>
			<p>Once appproved successfully or unsuccessfully, the suggetion will be removed from this table automatically.</p>
			<?php $this->sug_obj->prepare_items();?>
			<form method="post">
			<?php $this->sug_obj->display(); ?>
			</form>
		</div>
	<?php
	}
	public function clms_page() {
		?>
		<div class="wrap">
			<h1>Claimed Businesses</h1>
			<p>If appproved unsuccessfully, the entry will be removed from this table automatically.</p>
			<?php $this->clm_obj->prepare_items();?>
			<form method="post">
			<?php $this->clm_obj->display(); ?>
			</form>
		</div>
	<?php
	}
}


add_action( 'plugins_loaded', function () {
	AdminList::get_instance();
} );


?>