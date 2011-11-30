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