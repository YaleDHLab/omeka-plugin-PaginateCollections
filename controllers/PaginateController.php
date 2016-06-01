<?php 

class Paginate_PaginateController extends Omeka_Controller_AbstractActionController {

  protected $_broseRecordsPerPage = self::RECORDS_PER_PAGE_SETTING; 

  public function init() {
    $this->_helper->db->setDefaultModelName('Item');
  }

  public function showAction() {

    // determine the collection the user is wanting to access
    $requested_url = $_SERVER['REQUEST_URI'];

    // urls to this route have the form: /collections/:id
    $split_url = explode("/", $requested_url);

    // from the split url extract the id of the requested collection
    $requested_collection_id = end($split_url);

    // establish a database connection with whith to place request
    mysql_connect("localhost", "root", "winter44");
    mysql_select_db("cherokeemetadata") or die("Could not select the cherokeemetadata database");

    // parse the query string to determine the page of items to return
    $requested_page = (empty($_GET['page'])) ? '1' : $_GET['page'];

    // query for the current page of records
    $item_query = "SELECT * FROM sheet1 limit 10 offset ".$requested_page.";";
    $query_response = mysql_query($item_query);

    //$first_item = mysql_fetch_assoc($query_response);
    $total_items = mysql_num_rows( $query_response );


    // define variable then pass it to the view
    $heyo = "test";
    $this->view->assign(compact('requested_url', 'requested_collection_id', 'total_items', 'query_response'));

  }
}
