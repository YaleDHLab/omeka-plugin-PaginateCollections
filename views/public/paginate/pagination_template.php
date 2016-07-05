<div class="pagination-container">
  <div class="pagination-outer">
    <div class="pagination-inner">
      <?php
        if ($total_pages > 1) {
          $page_array = range(1, $total_pages, 1);

          // PHP uses 0 based indexing for array slicing,
          // but pages use 1 based indexing
          $start_page = $requested_page - 3;
          $pages_to_display = 5;

          // ensure the array slice doesn't begin with a negative
          if ($start_page < 0) {
            $start_page = 0;
          };

          $page_slice = array_slice($page_array, $start_page, $pages_to_display);
          $query_param_root = "<a href=".
            $application_root.
            "collections/show/".
            $requested_collection_id.
            "?page=";

          // if there are more pages to the left, create a < div
          // and a << div
          if ( in_array($start_page, $page_array) ) {
            // first create the << div
            echo $query_param_root."1>";
            echo "<div class='page'>&lsaquo;&lsaquo;</div>";
            echo "</a>";

            echo $query_param_root. $start_page.">";
            echo "<div class='page'>&lsaquo;</div>";
            echo "</a>";
          };

          // create divs for each page number
          foreach ($page_slice as $i => $page_index) {
            echo $query_param_root.$page_index.">";
            if ($requested_page == $page_index) {
              echo "<div class='page current-page'>".$page_index."</div>";
            } else {
              echo "<div class='page'>".$page_index."</div>";
            };
            echo "</a>";
          };

          // if there are more pages to the right, create a > div
          // and a >> div
          $next_page = $start_page + $pages_to_display + 1;
          if ( in_array($next_page, $page_array) ) {
            echo $query_param_root.$next_page.">";
            echo "<div class='page'>&rsaquo;</div>";
            echo "</a>";

            echo $query_param_root. $total_pages .">";
            echo "<div class='page'?>&rsaquo;&rsaquo;</div>";
            echo "</a>";
          };
        }; 
      ?>
    </div>
  </div>
</div>
