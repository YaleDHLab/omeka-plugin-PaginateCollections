### About
This plugin implments pagination for the collections/show/:id routes within the [ani-yun-wiya Omeka theme](https://github.com/YaleDHLab/ani-yun-wiya). While there are native Omeka hooks to support pagination within collections/browse pages, there are no such hooks for colllections/show pages, so this simple plugin provides that functionality.

### Installation

```
# download the source within your omeka install's plugins directory
git clone https://github.com/YaleDHLab/plugin-PaginateCollections
mv plugin-PaginateCollections Paginate

# set the omeka db name, user, and password in lines 27 and 28
vim Paginate/controllers/PaginateController.php
```

If you then visit your omeka install's `/admin` dashboard, you should be able to install the plugin. After doing so, visiting a collections/show/:id route should display the pagination links. 
