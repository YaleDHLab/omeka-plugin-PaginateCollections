<?php 

class Paginate_PaginateController extends Omeka_Controller_AbstractActionController {

  protected $_autoCsrfProtection = true;
  protected $_browseRecordsPerPage = self::RECORDS_PER_PAGE_SETTING;

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

    // connect to mysql
    mysql_connect("localhost", "root", "winter44", true);
    mysql_select_db("omeka") or die("Could not select the omeka db");

    // check to see if the requested collection id is in the db and public
    $n_collections_query = "SELECT * FROM omeka_collections 
        WHERE id = ".$requested_collection_id 
        ." AND public = 1;";
    $n_collections_response = mysql_query($n_collections_query);
    $n_collections = mysql_num_rows($n_collections_response);

    // if the requseted collection id isn't in the omeka db, raise 404
    // nb, this could also be:
    // throw new Zend_Controller_Action_Exception('This page does not exist', 404);
    if ($n_collections == 0)
      throw new Omeka_Controller_Exception_404;

    // parse the query string to determine the page of items to return
    // if no page query param is provided, use page 1 as a placeholder
    $requested_page = (empty($_GET['page'])) ? '1' : $_GET['page'];

    // specify the number of records to display on each page
    $records_per_page = 10;

    // compute the required offset (which requires one to treat the current
    // page as a 0-based sequence)
    $required_offset = $records_per_page * ( (int)$requested_page - 1);

    // query for the current page of records
    $item_query = "SELECT * FROM omeka_items 
        WHERE collection_id = ".$requested_collection_id
        ." limit ".$records_per_page
        ." offset ".$required_offset." ;";
    $query_response = mysql_query($item_query);

    //$first_item = mysql_fetch_assoc($query_response);
    $total_items = mysql_num_rows( $query_response );

    // define variable then pass it to the view
    $this->view->assign(compact(
        'requested_url',
        'requested_collection_id',
        'total_items',
        'query_response',
        'n_collections_response'
    ));

    // calculate percentage completed 


  }
}
