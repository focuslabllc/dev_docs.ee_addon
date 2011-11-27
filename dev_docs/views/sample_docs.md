# Notes for Project

These notes are intended to be only for the developer(s) on the project.

::start::


# Style Samples

This is a small paragraph. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.

## This is a title, H2

This is a small paragraph. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.

<table>
<thead>
	<tr>
		<th>Label</th>
		<th>Short Name</th>
		<th>Type</th>
	</tr>
</thead>
<tbody>
	<tr>
		<td>Blog Intro</td>
		<td>blog_intro</td>
		<td>Textarea</td>
	</tr>
	<tr>
		<td>Blog Body</td>
		<td>blog_body</td>
		<td>Wygwam</td>
	</tr>
	<tr>
		<td>Leading Image</td>
		<td>blog_leading_image</td>
		<td>File</td>
	</tr>
</tbody>
<thead>
	<tr>
		<th>Second Label</th>
		<th>Short Name</th>
		<th>Type</th>
	</tr>
</thead>
<tbody>
	<tr>
		<td>Search excerpt</td>
		<td>blog_search_excerpt</td>
		<td>Textarea</td>
	</tr>
</tbody>
</table>


### This is a subhead, H3

#### Heading 4

##### Heading 5

###### Heading 6

> This is a block quote. I'll admit it's not the most exciting block quote ever devised.

Simple list:

- one
- two
- three

Multi-level list:

- one
	- aye
	- bee
	- see
- two
	- x
	- y
- three

Mixed list:

- Point one
- Point two
	1. Step 1
	2. Step 2
	3. Step 3
- Point three
	- Sub point 1
	- Sub point 2


### Coding

This `is some code, "isn't it"`. Watch those quote marks! Now for some pre-formatted text:

	$text = str_replace("<p>%::%</p>","",$text);
	$text = str_replace("%::%</p>","",$text);
	$text = str_replace("%::%","",$text);


# Site Overview

This file contains details on the workings of EE for mysite.com. Every template is also documented with helpful information regarding the EE setup. Developers on the project include the full dev team at [Focus Lab, LLC](http://focuslabllc.com)

## Naming Conventions

Across the Channels, Custom Fields and Upload Directories we use the same naming convention. It it to keep things organized and as predictable as possible. The convention uses a "prefix" of the Channel short name in front of any other words. For example:

- Channel name is "Pages"
- 3 custom fields for Meta Description, Primary Image & Body Content
- 1 Upload directory for the images
- My names would look something like:
	- Channel short name: "pages"
	- Custom fields: "pages\_meta\_description", "pages\_primary\_image", & "pages\_body\_content"
	- Upload Directory: "Pages Image Uploads"

This convention is used when applicable in Status Groups, Categories & closely related Channels as well.

## Version Control

Files for this site are kept in a Git repository. All files are versioned back to original static site preview with the client. Developers check in to Beanstalk and we deploy from Beanstalk to all non-local environments (development, staging & production). Ignored files include .htaccess, caches & upload directories. See the ./.gitignore file for the full ignore list.


# Server Environments

mysite.com is running in 4 environments. Local copies are on each independent developer's computer. There is a private Development and private Staging environment and a public Production environment.

## Environments

### Local

- Specific to each developer.
- Developers are encouraged to run the latest stable versions of PHP and MySQL

### Development

- Located at dev.mysite.com
- PHP version 5.2.14
- MySQL version 5.0.84
- Apache 2.2.17

### Staging

- Located at staging.mysite.com
- PHP version 5.3.2
- MySQL version 5.0.91
- Apache 2.2.17

### Production

- Located at mysite.com
- PHP version 5.3.2
- MySQL version 5.0.91
- Apache 2.2.17


## Cross-environment config.php

This site uses the 3rd Party NSM Config Bootstrap by Leevi Graham of Newism. You can find more information on that multi-environment approach [here](http://expressionengine-addons.com/nsm-config-bootstrap)

Included in the config\_bootstrap.php file we also set the path for the 3rd Party plugin [ED ImageResizer](http://github.com/erskinedesign/ED-Imageresizer)

This may also be helpful for creating a multi-server environment config.php base file: <http://eeinsider.com/articles/multi-server-setup-for-ee-2/>


# Channels & Related Groups

Our site currently has 2 Channels using a total of 1 Category Group. Each Channel has its own Custom Field Group. Every Channel uses the default Status Group.

## Pages

This Channel is for our site's static content. It houses things like About Us and Contact. 

- No Categories are used
- The primary Template used for this Channel's content is site/\_pages
- Default Status Groups is used
- 3 Custom Fields are used
	- page\_extra\_meta (Textarea, not required, searchable, no formatting, using Textarea so content manager can input multiple full meta tags since they know how)
	- page\_banner (Text Input, not required, not searchable, no formatting, uses file chooser)
	- page\_content (Textarea, required, searchable, XHTML formatting)

The 3rd Party Add-on [Structure](http://buildwithstructure.com) is in large use for our Pages Channel. We use it to build the hierarchy of Pages and also the HTML Titles, Menus & Breadcrumbs on the front-end. Pages are primarily run through the template site/\_pages (configurable per page and within the Structure settings). There are a few pages that have their own custom templates which is mentioned below in the Templating section of this document.

## Blog

This Channel is (obviously) the site's blog. It is a pretty generic blog setup.

- 1 Category Group is used
- The Channel has it's own Template Group with a few templates
- The default Status Group is used
- 6 Custom Fields are used
	- blog\_meta\_description (Text Input, not required, searchable, no formatting)
	- blog\_introduction (Textarea, required, searchable, XHTML formatting)
	- blog\_thumbnail (Text Input, not required, not searchable, no formatting, uses file chooser)
	- blog\_tags (Text Input, not required, searchable, no formatting, basic implementation of "tags" for our entries)
	- blog\_gallery (Matrix with File Chooser & Text Input, Maximum of 10 rows)
	- blog\_full\_content (Wygwam, uses standard toolbar & Default Upload Directory for uploads)

The Blog has the following pages on the site:

- Blog landing page (/blog)
- Archive page (/blog/archive)
- Authors Page (/blog/authors)
- Categories list (/blog/categories)
- Single Entries (/blog/blog-title-here)

Each template used in the pages above are discussed in more detail in the Tempalting section of this document. 

## Sample Table

Sample custom field table:

<table>
<thead>
	<tr>
		<th>Label</th>
		<th>Short Name</th>
		<th>Type</th>
	</tr>
</thead>
<tbody>
	<tr>
		<td>Blog Intro</td>
		<td>blog_intro</td>
		<td>Textarea</td>
	</tr>
	<tr>
		<td>Blog Body</td>
		<td>blog_body</td>
		<td>Wygwam</td>
	</tr>
	<tr>
		<td>Leading Image</td>
		<td>blog_leading_image</td>
		<td>File</td>
	</tr>
	<tr>
		<td>Search excerpt</td>
		<td>blog_search_excerpt</td>
		<td>Textarea</td>
	</tr>
</tbody>
</table>


# Member Groups

Our site is maintained by a couple of developers. The content is reviewed by a site Editor. The blog articles are written by a combination of the site Editor and guest authors. Thus our *utilized* Member Groups are:

- **Super Admins** - obviously the developers on the site with full access to everything
- **Editors** - The site editor. Publish/Edit access to all Channels, can edit categories, access to Structure, the works. They also have access to Member management so they can add/edit the "guest authors" at will. *NO* access to Templates, other Modules, Any admin preferences etc.
- **Authors** - Guest authors. Publish/Edit access to *their* entries in the Blog channel. Cannot edit categories or access Structure. When they create blog entries the Status is set to Closed so the Editor can review before posting to the site. Authors do still have access to the Control Panel for the management of their entries.

Other default Member Groups are not widely used. There is no site registration so "Allow New Member Registration" is set to "No" and all "guest authors" are registered manually by the Editor.


# Templates

Because our site is so small there are only a few templates in play. There are a few embeds to keep the site design easily changed over time and we don't use any Snippets. There are a few Global Variables set in our config bootstrap the are used in some templates (noted below).

All of our templates are saved (and versioned) as flat files. The location of those files is (accountroot)/templates.

## Template Group / Template Structure

- embeds
	- embeds/\_header
	- embeds/\_footer
	- embeds/\_main_menu
- site
	- site/index (home page only, hardcoded Pages entry)
	- site/\_pages (default Structure Pages template)
- blog
	- blog/index (blog landing page)
	- blog/categories (list of categories & entry count, links to pages like /blog/category/tech)
	- blog/authors (list of our authors, nothing special)
	- blog/\_entry (single blog entry page, the default blog entry template in Structure)
	- blog/archive (archive by month for our blog entries)
- specific\_pages
	- specific\_pages/\_contact (Structure: template with contact form used only for our Contact Page)
	- specific\_pages/\_map (Structure: template with interactive map used only for our Map page)
	- specific\_pages/\_social (Structure: template with multiple social RSS feed integrations only used for our Social page)

## Global Variables

There are only a few Global Variables in play across this site. They are all set in our config bootstrap. The most common use is running a template conditional to see what environment we are in. For example, we only load our statistics tracking script if we're in our production environment so that might look like this:

	{if env == 'prod'}
		// Javascript inserted here
	{/if}

## Inline Commenting

We heavily comment our templates in-line when there are unclear steps or chunks of code. For specifics on what each template does open each template. Each template starts with a commented bit of info including the purpose of the template, the cache settings, PHP mode (if applicable) and potential embedded variables.



# 3rd Party Add-ons

We are only using a handful of Add-ons that alter the site significantly. They are:

- Structure
- NSM Transplant
- ER Developer Toolbar

## Structure

[Structure](http://buildwithstructure.com) is used to better manage the majority of our URLs (foregoing the template\_group/template approach EE does out of the box). We use Structure to also help the client see (and easily re-arrange) their site's Pages hierarchy.

Structure has some handy tags in use across the site. The HTML Title is built with Structure. The sub navigation is all built with Structure. The primary navigation, however, is not. (That is hard-coded in the embeds/\_main\_menu template.)

Structure allows for the customizing of templates on a per-entry basis. We use that on a few entries and that is the sole reason for the entire "specific\_pages" Template Group.

## NSM Transplant

We are using [NSM Transplant](http://expressionengine-addons.com/nsm-transplant) to cut down on queries across our templates. It allows us to occasionally save pieces of data from within a Channel Entries loop and use it outside that loop later in the template. You can find more on that here: <http://expressionengine-addons.com/nsm-transplant>. This only affects our templates, not the back-end.

## ER Developer Toolbar

[ER Developer Toolbar](http://erikreagan.com/projects/er-developer-toolbar/) is just a toolbar shown on the front-end of the website. It is a module with links to pages on the back-end making it easier to develop the site and manage content for the Editor. There are Module settings that allow toolbar configurations for each member group. The toolbar only displays for Super Admins and Editors. More info here: <http://erikreagan.com/projects/er-developer-toolbar/>