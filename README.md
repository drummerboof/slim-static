Slim Static
===========

A base slim site for creating static sites. 

- Pages are automatically routed based on the view files in app/views.
- If debug is set to false in app/config/app.php, a cache file of the view file locations is saved in app/cache. This should be deleted for new pages to be picked up.
- Support for layouts and partials using the LayoutView.
- PageManager class allows access to page details from within the view via $this->pages() and $this->page()
- Hooks for adding custom functionality to pages in the form of routes.path.page (for /path/page)
 
