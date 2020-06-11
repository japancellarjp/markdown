# japancellarjp\markdown

php Markdown Subset

base : [cebe/markdown](https://github.com/cebe/markdown) GithubMarkdown.


## install

```
composer config repositories.japancellarjp/markdown vcs https://github.com/japancellarjp/markdown
composer require japancellarjp\markdown
```

## use

~~~
php -r '
require __DIR__ . "/vendor/autoload.php";
$markdown = new japancellarjp\markdown\MarkdownSubset();
$str = "
# disable header 

disable hr
***
---

*enable em*
_disable em_


enable list only &#42;
* list1
  * list11
- list1
  - list11

```
<?php
  echo \"code\";
```

> blockquote1
> > blockquote11
";
echo $markdown->parse(htmlentities($str, ENT_NOQUOTES, "UTF-8", false));
'
~~~

```
<p># disable header </p>
<p>disable hr<br />
***<br />
---</p>
<p><em>enable em</em><br />
_disable em_</p>
<p>enable list only &#42;</p>
<ul>
<li>list1<ul>
<li>list11<br />
- list1<br />
- list11</li>
</ul>
</li>
</ul>
<pre><code>&lt;?php
  echo "code";
</code></pre>
<blockquote><p>blockquote1</p>
<blockquote><p>blockquote11</p>
</blockquote>
</blockquote>
```

## disable tag

```
hr (*-_)
list (+-)
strong (~)
inner code (`)
code (~~~)
all html tag
html link []()
html link http://
img link ![]()
```

## change tag
```
> to &gt;
```

## license

MIT