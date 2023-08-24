# Wp Post Alternative Content Grid

This is a wordpress plugin which help you to create post grids using shortcodes.

## Introduction

Use this plugin to show post grid in your post, page anywhere in your website.
```
[wp_post_custom_post_grid]
```

## Available 'post_type' Values
1. `'post'`
2. `'page'`
3. `'custom_post_type'`

example:
```
[wp_post_custom_post_grid post_type="service"]
```

## Available 'orderby' Values and Corresponding 'order' Values

Here are the available `'orderby'` values and the corresponding `'order'` values you can use in your custom queries:

1. `'ID'`

   - Orders by post ID.
   - `'order'` values: `'ASC'` (ascending) or `'DESC'` (descending)
   - ```
        [wp_post_custom_post_grid post_type="service" orderby="ID" order="ASC"]
        ```

2. `'author'`

   - Orders by author's ID.
   - `'order'` values: `'ASC'` (ascending) or `'DESC'` (descending)
   - ```
        [wp_post_custom_post_grid post_type="service" orderby="author" order="ASC"]
        ```

3. `'title'`

   - Orders by post title.
   - `'order'` values: `'ASC'` (ascending) or `'DESC'` (descending)
   - ```
        [wp_post_custom_post_grid post_type="service" orderby="title" order="ASC"]
        ```

4. `'name'`

   - Orders by post slug (post name).
   - `'order'` values: `'ASC'` (ascending) or `'DESC'` (descending)
   - ```
        [wp_post_custom_post_grid post_type="service" orderby="name" order="ASC"]
        ```

5. `'date'`

   - Orders by post publication date.
   - `'order'` values: `'ASC'` (oldest first) or `'DESC'` (newest first)
   - ```
        [wp_post_custom_post_grid post_type="service" orderby="date" order="ASC"]
        ```

6. `'modified'`

   - Orders by post modification date/update date.
   - `'order'` values: `'ASC'` (oldest first) or `'DESC'` (newest first)
   - ```
        [wp_post_custom_post_grid post_type="service" orderby="modified" order="ASC"]
        ```

7. `'rand'`
   - Orders randomly.
   - ```
        [wp_post_custom_post_grid post_type="service" orderby="rand"]
        ```