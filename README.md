## Wordpress plugin KSASK Add Post Shortcode

A shortcode that displays a form with 2 fields: title and text. 
After submitting the form, the plugin creates a new unpublished post with 
a title from the title field and text from the text field, 
and also sends an email to the administrator's email with the title 
and text of the post.

## Install:

You can install the package via composer:

    composer require ksask/ksa-add-post dev-master

## Use plugin:

Insert the shortcode where you want the form to appear:

    [ksa_add_post_form]