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