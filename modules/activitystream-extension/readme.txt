=== ActivityStream extension ===
Contributors: pfefferle
Donate link: http://notizblog.org
Tags: Activities, Activity Stream, Feed, RSS, Atom, OStatus, OStatus Stack, JSON
Requires at least: 3.2
Tested up to: 3.2.1
Stable tag: 0.8

ActivityStrea.ms syntax for WordPress (Atom and JSON)

== Description ==

An extensions which ActivityStream ([activitystrea.ms](http://www.activitystrea.ms)) support to your WordPress-blog

Atom Example:

` <entry>
    <id>http://notizblog.org/?p=1775</id>
    <author>
      <name>Matthias Pfefferle</name>
      <uri>http://notizblog.org</uri>
    </author>
    .
    .
    .
    <activity:verb>http://activitystrea.ms/schema/1.0/post</activity:verb>

    <activity:object>
      <activity:object-type>http://activitystrea.ms/schema/1.0/article</activity:object-type>
      <id>tag:notizblog.org,2009-07-13:/post/1775</id>
      <title type="html"><![CDATA[Matthias Pfefferle posted a new blog-entry]]></title>
      <link rel="alternate" type="text/html" href="http://notizblog.org/2009/07/14/webstandards-kolumne/" />
    </activity:object>
  </entry>`

JSON Example:

`{
  items: [{
    published: "2011-01-30T21:34:48Z",
    verb: "post",
    target: {
      id: http://notizblog.org/feed/json,
      url: http://notizblog.org/feed/json,
      objectType: "blog",
      displayName: "notizBlog"
    },
    object: {
      id: http://notizblog.org/?p=322
      displayName: "wsn?",
      objectType: "article",
      summary: "wasn?",
      url: http://notizblog.org/?p=322
    },
    .
    .
    .
  }]
}`

== Installation ==

* Upload the whole folder to your `wp-content/plugins` folder
* Activate it at the admin interface

Thats it

== Changelog ==

= 0.8 =
* some JSON changes to match spec 1.0
* changed the HTML discovery-links
* added post_thumbnail support

= 0.7.1 =
* updated to new JSON-Activity changes

= 0.7 =
* deprecated `<activity:subject>`
* enriched Atom `<author />`

= 0.6 =
* added json feed
* pubsubhubbub for json

= 0.5 =
* some OStatus compatibility fixes
* added `<activity:subject>`
* added `<activity:target>`

= 0.3 =
* Fixed a namespace bug
* Added autodiscovery link