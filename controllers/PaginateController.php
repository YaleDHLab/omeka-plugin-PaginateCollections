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

    // remove the query params from the last element of split_url
    $requested_collection_id = explode( "?", end($split_url) )[0];

    // parse out the application root for the view
    $application_root = implode("/", array_slice($split_url, 0, 2) )."/";

    // connect to mysql
    mysql_connect("localhost", "root", "winter44", true);
    mysql_select_db("omeka") or die("Could not select the omeka db");

    ////////////
    // header //
    ////////////
    
    // pass the collection_title and collection_description to the view
    $collection_title_query = "SELECT text 
      from omeka_element_texts 
      where element_id = 50
      and record_id = ".$requested_collection_id.";";

    $collection_title = mysql_query($collection_title_query); 

    $collection_description_query = "SELECT text 
      from omeka_element_texts 
      where element_id = 41
      and record_id = ".$requested_collection_id.";";

    $collection_description = mysql_query($collection_description_query); 

    //////////
    // body //
    //////////

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
    $item_query = "select oi.id, of.filename, oet50table.oettitle, oet39table.oetauthor,  oet40table.oetdate,  oet48table.oetboxfolder, oet137table.oetpercentcomplete
      from omeka_items as oi 
      join (
        select filename, item_id from omeka_files as omfi 
         WHERE id IN (
                     SELECT min(id) 
                       FROM omeka_files 
                      GROUP BY item_id
                   )
      ) as of
      on oi.id = of.item_id
      left join (select record_id, element_id, text as oettitle from omeka_element_texts where element_id in (50)) as oet50table
      on oi.id = oet50table.record_id
      left join (select record_id, element_id, text as oetdate from omeka_element_texts where element_id in (40)) as oet40table
      on oi.id = oet40table.record_id
      left join (select record_id, element_id, text as oetauthor from omeka_element_texts where element_id in (39)) as oet39table
      on oi.id = oet39table.record_id
      left join (select record_id, element_id, text as oetboxfolder from omeka_element_texts where element_id in (48)) as oet48table
      on oi.id = oet48table.record_id
      left join (select record_id, element_id, text as oetpercentcomplete from omeka_element_texts where element_id in (137)) as oet137table
      on oi.id = oet137table.record_id
      where collection_id = ".$requested_collection_id." order by cast(oet137table.oetpercentcomplete as unsigned) limit ".$records_per_page." offset ".$required_offset.";";

    $query_response = mysql_query($item_query);

    // query for the total number of items,
    // and the number of items that have been transcribed
    $total_files_query = "select count(id)
      from omeka_files
      where item_id in (
        select id 
        from omeka_items 
        where collection_id = ".$requested_collection_id."
      );";

    // Only transcribed pages are sent to the omeka_element_texts
    // database, so querying for those tells us how many files 
    // (ie pages) from the current collection have been trasncribed
    $total_files_transcribed_query = "select count(id) 
      from omeka_element_texts 
      where element_id = 136
      and record_id in (
        select id 
        from omeka_files 
        where item_id in (
          select id 
          from omeka_items 
          where collection_id = ".$requested_collection_id."
        )
      );
    ";

    $total_files = mysql_result( mysql_query($total_files_query), 0 );
    $total_files_transcribed = mysql_result( mysql_query($total_files_transcribed_query), 0 );
    $total_percent_complete = ( $total_files_transcribed / $total_files ) * 100;

    // send the view the total number of pages for the current collection
    $total_items_query = "select count(id) 
      from omeka_items 
      where collection_id = ".$requested_collection_id;
    $total_items = mysql_result( mysql_query($total_items_query), 0);
    $total_pages = $total_items / $records_per_page;

    // define variable then pass it to the view
    $this->view->assign(compact(
        'requested_url',
        'requested_collection_id',
        'total_files',
        'total_files_transcribed',
        'total_percent_complete',
        'query_response',
        'n_collections_response',
        'collection_title',
        'collection_description',
        'application_root',
        'total_pages',
        'requested_page'
    ));
  }
}
