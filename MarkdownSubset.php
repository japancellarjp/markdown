<?php
namespace japancellarjp\markdown;

class MarkdownSubset extends \cebe\markdown\GithubMarkdown {
  public $enableNewlines = true;
  protected $escapeCharacters = [
    // from Markdown
    '\\', // backslash
    '`', // backtick
    '*', // asterisk
    //'_', // underscore
    '{', '}', // curly braces
    '[', ']', // square brackets
    '(', ')', // parentheses
    '#', // hash mark
    //'+', // plus sign
    //'-', // minus sign (hyphen)
    //'.', // dot
    //'!', // exclamation mark
    '<', '>',
    // added by GithubMarkdown
    ':', // colon
    '|', // pipe
  ];


  //--------------------
  // disable parse,identify

  // disable all htmltag
  protected $inlineHtmlElements = [];
  protected $selfClosingHtmlElements = [];

  // disable '[title](url)'
  protected function parseUrl($markdown)
  {
    return [['text', substr($markdown, 0, 4)], 4];
  }

  // disable '[title](url)'
  protected function parseLink($markdown)
  {
    return [['text', '['], 1];
  }

  // disable '![alt](image url)'
  protected function parseImage($markdown)
  {
    return [['text', '!['], 2];
  }

  // disable '`'
  protected function parseInlineCode($text)
  {
    return [['text', '`'], 1];
  }

  // disable double htmlspecialchars ('&lt;' -> '&amp;lt;')
  protected function renderCode($block)
  {
    $class = isset($block['language']) ? ' class="language-' . $block['language'] . '"' : '';
    return "<pre><code$class>" . htmlspecialchars($block['content'] . "\n", ENT_NOQUOTES | ENT_SUBSTITUTE, 'UTF-8', false) . "</code></pre>\n";
  }

  protected function parseEntity($text)
  {
    return false;
    //return [['text', '&'], 1];
  }

  // '*','_' -> '*'
  /**
   * @marker *
   */
  protected function parseEmphStrong($text)
  {
    return parent::parseEmphStrong($text);
  }

  // disable '***','---','___'
  protected function identifyAHr($line)
  {
    return false;
  }
  protected function identifyHr($line)
  {
    return false;
  }

  // '*','+','-' -> '*'
  protected function identifyBUl($line)
  {
    return false;
  }
  protected function identifyUl($line)
  {
    $l = $line[0];
    return ($l === '*') && (isset($line[1]) && (($l1 = $line[1]) === ' ' || $l1 === "\t")) ||
           ($l === ' ' && preg_match('/^ {0,3}[\*][ \t]/', $line));
  }

  // disable '#'
  protected function identifyHeadline($line, $lines, $current)
  {
    return false;
  }

  // '```'.'~~~' -> '```'
  protected function identifyFencedCode($line)
  {
    return ($line[0] === '`' && strncmp($line, '```', 3) === 0) ||
      (isset($line[3]) && (
        ($line[3] === '`' && strncmp(ltrim($line), '```', 3) === 0)
      ));
  }

  // '>' -> '&gt;'
  protected function identifyQuote($line)
  {
    return substr($line, 0, 4) === '&gt;' && (!isset($line[4]) || ($l1 = $line[4]) === ' ');
  }
  protected function consumeQuote($lines, $current)
  {
    // consume until newline
    $content = [];
    for ($i = $current, $count = count($lines); $i < $count; $i++) {
      $line = $lines[$i];
      if (ltrim($line) !== '') {
        if (substr($line, 0, 4) === '&gt;' && !isset($line[4])) {
          $line = '';
        } elseif (strncmp($line, '&gt; ', 5) === 0) {
          $line = substr($line, 5);
        }
        $content[] = $line;
      } else {
        break;
      }
    }

    $block = [
      'quote',
      'content' => $this->parseBlocks($content),
      'simple' => true,
    ];
    return [$block, $i];
  }
}
