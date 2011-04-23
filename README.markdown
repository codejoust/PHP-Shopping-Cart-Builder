PHP Shopping Cart Builder
-------

A JSON-based php datastore to easily list products for sale on a website.

Uses knockout.js and jquery for the admin.

The admin.php file is where setup takes place - the username and password values need to be entered, along with a directory setup for image uploads.
Preferably, the image upload directory should be outside the folder that these tools are kept in (along with the frontend page).

For instance:
* site/index.php - main site homepage, static or dynamic
* site/cart.php --> includes site/cartadmin/render.php, where the products will be shown
* site/images/product_uploads/ --> referenced in site/cartadmin/admin.php for image uploads, apache writable (either apache group writable or globally writable)
* site/cartadmin/* (all these files making up the shopping cart administrative interface)
* site/cartadmin/data/data.json and data/data-bk.json also need to be writable by apache

The render.php file is where the products are rendered, so changing that file will change the output.

site/cart.php, for example:

    <?php require_once 'cartadmin/render.php'; ?>
    <h2>My Stuff</h2>
    <?php show_items(); ?>

    <h4>View your cart</h4>
    <?php view_cart_button(); ?>
    
Enjoy!
