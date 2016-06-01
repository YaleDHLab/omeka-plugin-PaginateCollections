<?php 

class Paginate_PaginateController extends Omeka_Controller_AbstractActionController {

  protected $_broseRecordsPerPage = self::RECORDS_PER_PAGE_SETTING; 

  public function init() {
    $this->_helper->db->setDefaultModelName('Item');
  }

  public function showAction() {
    $heyo = "test";

    $this->view->assign(compact('heyo'));


  }

}


