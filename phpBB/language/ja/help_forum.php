<?php
/***************************************************************************
 *                         help_forum.php [japanese]
 *                            -------------------
 *   begin                : Saturday Jul 7, 2007
 *   copyright            : (C) Cai
 *   email                : cai.0407@gmail.com
 *
 *   $Id$
 *
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

// 
// To add an entry to your forum guide simply add a line to this file in this format:
// $faq[] = array("question", "answer");
// If you want to separate a section enter $faq[] = array("--","Block heading goes here if wanted");
// Links will be created automatically
//
// DO NOT forget the ; at the end of the line.
// Do NOT put double quotes (") in your BBCode guide entries, if you absolutely must then escape them ie. \"something\"
//
// The BBCode guide items will appear on the BBCode guide page in the same order they are listed in this file
//
// If just translating this file please do not alter the actual HTML unless absolutely necessary, thanks :)
//
// In addition please do not translate the colours referenced in relation to BBCode any section, if you do
// users browsing in your language may be confused to find they're BBCode doesn't work :D You can change
// references which are 'in-line' within the text though.
//

//

if (!defined('IN_PHPBB')){
  exit;
}
  
$help = array(
              array(
                    0 => '--',
                    1 => 'ガイダンス'
                    ),
              array(
                    0 => "はじめに", 
                    1 => "<p>このフォーラムは Mozilla 製品とそのアドオン等のユーザ・開発者の相互サポート及びそれらに関連する情報と話題を扱うコミュニティフォーラムです。<br/>ニュースサイトと併せて Mozilla の普及と啓蒙を目的としています。<br/>[注：ここでいう開発者は Mozilla 製品そのものを開発する方ではありません。]</p>"
                    ),
              array(
                    0 => "質問する前に", 
                    1 => "<ul>
  <li><p>まずは検索機能を利用してみましょう。これまでに他のユーザから同じような質問が投稿されているかもしれません。トップページメニューの[検索]をクリックしてキーワードを入れてみましょう。</p></li>
  <li><p>FAQ やナレッジベース等に同様のケースがないか読んでみましょう（下記リンクを参照ください）。</p></li>
</ul>"
                    ),
              array(
                    0 => "質問するときは", 
                    1 => "<ul>
  <li><p>フォーラムは扱う話題によっていくつかのカテゴリコーナーに分かれています。もっとも適していると思われるコーナーで質問しましょう。</p></li>
  <li><p>Mozilla 製品には様々な種類があります。また Windows や Mac OS だけでなく Linux などでも動作します。そのため、どの製品と OS をお使いかによって問題の切り分け方が違いますし、回答の仕方が異なります。<span style=\"color:red;\"><strong>質問には必ずお使いの製品名・バージョンと　お使いの OS の種類を含めてください。</strong></span></p></li>
  <li><p>誰もが気持ちよく利用できるよう丁寧に質問しましょう。回答してくれた方へのお礼や感謝の気持ちを忘れずに。できれば結果や経過を書いて他の方が参考にできるようにしましょう。</p></li>
</ul>"
                    ),
              array(
                    0 => "告知・案内は", 
                    1 => "<p>トップの「お知らせ」コーナーに適時告知等を掲載します。ぜひ一読ください。<br/>参照： お知らせ<br/><a href=\"http://forums.mozillazine.jp/viewforum.php?f=1\">http://forums.mozillazine.jp/viewforum.php?f=1</a></p>"
                    ),
              array(
                    0 => "問合せは",
                    1 => "<p>「MozillaZine.jp について」コーナーにお気軽にお寄せください。<br/>参照： MozillaZine.jp について<br/><a href=\"http://forums.mozillazine.jp/viewforum.php?f=8\">http://forums.mozillazine.jp/viewforum.php?f=8</a></p>"
                    ),
              array(
                    0 => "--", 
                    1 => "リンク集"
                    ),
              array(
                    0 => "サポート", 
                    1 => "<p>Mozilla Japan - Firefox サポート (日本語)<br/>
<a href=\"http://mozilla.jp/support/firefox/\">http://mozilla.jp/support/firefox/</a><br/>
Mozilla Japan - Thunderbird サポート (日本語)<br/>
<a href=\"http://mozilla.jp/support/thunderbird/\">http://mozilla.jp/support/thunderbird/</a><br/>
不具合診断チャート - もじら組 Wiki (日本語)<br/>
<a href=\"http://wiki.mozilla.gr.jp/wiki.cgi?page=chart\">http://wiki.mozilla.gr.jp/wiki.cgi?page=chart</a><br/>
基本的なトラブルシューティング - Firefox サポート (日本語)<br/>
<a href=\"http://support.mozilla.com/ja/kb/%E5%9F%BA%E6%9C%AC%E7%9A%84%E3%81%AA%E3%83%88%E3%83%A9%E3%83%96%E3%83%AB%E3%82%B7%E3%83%A5%E3%83%BC%E3%83%86%E3%82%A3%E3%83%B3%E3%82%B0\">http://support.mozilla.com/ja/kb/%E5%9F%BA%E6%9C%AC%E7%9A%84%E3%81%AA%E3%83%88%E3%83%A9%E3%83%96%E3%83%AB%E3%82%B7%E3%83%A5%E3%83%BC%E3%83%86%E3%82%A3%E3%83%B3%E3%82%B0</a><br/>
用語集 - Firefox サポート (日本語)<br/>
<a href=\"http://support.mozilla.com/ja/kb/%E7%94%A8%E8%AA%9E%E9%9B%86\">http://support.mozilla.com/ja/kb/%E7%94%A8%E8%AA%9E%E9%9B%86</a></p>"),
              array(
                    0 => "ナレッジベース", 
                    1 => "<p><p>Firefox サポート - ホームページ (日本語)<br/>
<a href=\"http://support.mozilla.com/ja/kb/Firefox+Support+Home+Page\">http://support.mozilla.com/ja/kb/Firefox+Support+Home+Page</a><br/>
Mozilla Japan - Thunderbird サポート - ナレッジベース (日本語)<br/>
<a href=\"http://mozilla.jp/support/thunderbird/kb/\">http://mozilla.jp/support/thunderbird/kb/</a><br/>
Knowledge Base - MozillaZine Knowledge Base (英語)<br/>
<a href=\"http://kb.mozillazine.org/Knowledge_Base\">http://kb.mozillazine.org/Knowledge_Base</a><br/>
もじら組 Wiki (日本語)<br/>
<a href=\"http://wiki.mozilla.gr.jp/\">http://wiki.mozilla.gr.jp/</a></p>"
                    ),
              array(
                    0 => "その他", 
                    1 => "<p>The SeaMonkey&reg; Project (英語)<br/>
<a href=\"http://www.seamonkey-project.org/\">http://www.seamonkey-project.org/</a><br/>
SeaMonkey.jp (日本語)<br/>
<a href=\"http://seamonkey.jp/\">http://seamonkey.jp/</a><br/>
Calendar プロジェクト - Lightning と Sunbird&reg;のホーム (日本語)<br/>
<a href=\"http://www.mozilla-japan.org/projects/calendar/\">http://www.mozilla-japan.org/projects/calendar/</a><br/>
Camino. Mozilla Power, Mac Style (英語)<br/>
<a href=\"http://caminobrowser.org/\">http://caminobrowser.org/</a><br/>
Mozilla Japan (日本語)<br/>
<a href=\"http://mozilla.jp/\">http://mozilla.jp/</a><br/>
Mozilla L10N :もじふぉ (日本語)<br/>
<a href=\"http://forums.firehacks.org/l10n/\">http://forums.firehacks.org/l10n/</a><br/>
もじら組 (日本語)<br/>
<a href=\"http://www.mozilla.gr.jp/\">http://www.mozilla.gr.jp/</a><br/>
Mozilla Developer Center 英語版<br/>
<a href=\"https://developer.mozilla.org/En\">https://developer.mozilla.org/En</a><br/>
Mozilla Developer Center 日本語版<br/>
<a href=\"https://developer.mozilla.org/ja\">https://developer.mozilla.org/ja</a><br/>
DevNews 英語版<br/>
<a href=\"https://developer.mozilla.org/devnews/\">https://developer.mozilla.org/devnews/</a><br/>
DevNews 日本語版<br/>
<a href=\"https://developer.mozilla.org/ja/DevNews\">https://developer.mozilla.org/ja/DevNews</a><br/>
Mozilla Cross-Reference<br/>
<a href=\"http://mxr.mozilla.org/\">http://mxr.mozilla.org/</a></p>"
                    )
              );

//
// This ends the forum guide entries
//

?>