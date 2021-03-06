<?php
/**
*
* @package Anti-Spam ACP
* @copyright (c) 2008 EXreaction
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

function reg_check($text)
{
  $endings = array('/');
  foreach($endings as $end){
    $text = str_replace( $end, '\\'.$end, $text);
  }
  $text = str_replace( '*', '.*', $text);
  return '/'.$text.'/';
}

class spam_words
{
	public $messages = array();
	public $spam_words = array();
	public $spam_flags = 0; // number of times a message is flagged as spam

	public function __construct()
	{
		global $cache, $db;

		$this->spam_words = $cache->get('_spam_words');
		if ($this->spam_words === false)
		{
			$this->spam_words = array();
			$result = $db->sql_query('SELECT * FROM ' . SPAM_WORDS_TABLE);
			while ($row = $db->sql_fetchrow($result))
			{
				if ($row['word_regex_auto'])
				{
					$row['word_text'] = $this->build_regex($row['word_text']);
				}
				$this->spam_words[] = $row;
			}
			$db->sql_freeresult($result);
			$cache->put('_spam_words', $this->spam_words);
		}
	}

	public function check_messages()
	{
		if (!sizeof($this->spam_words))
		{
			return;
		}
		$str_from = array('<', '>', '[', ']', '.', ':');
		$str_to = array('&lt;', '&gt;', '&#91;', '&#93;', '&#46;', '&#58;');

		foreach ($this->messages as $key => $text)
		{
			$text = str_replace($str_to, $str_from, htmlspecialchars_decode($text));
      foreach ($this->spam_words as $word)
			{
        if ($word['word_type'] == 1 && $key != 'username'){
          continue;
        }
        if ($word['word_type'] == 2 && $key != 'title'){
          continue;
        }
        if ($word['word_type'] == 3 && $key != 'message'){
          continue;
        }
				if ($word['word_regex'] || $word['word_regex_auto'])
				{
          $count = preg_match($word['word_text'], $text);
					if ($count > 0)
					{
						$this->spam_flags += $count;
					}
				}
				else if($word['word_white_pattern'])
        {
          $reg_text = reg_check($word['word_text']);
          $count = preg_match($reg_text, $text);
          if ($count === 0)
          {
            $this->spam_flags++;
          }
				}
        else
        {
          $count = 0;
          $reg_text = reg_check($word['word_text']);
           $count = preg_match($reg_text, $text);
          if ($count > 0){
            $this->spam_flags += $count;
          }
        }
			}
		}
	}


	public function build_regex($text)
	{
		$regex_ary = array(
			'a'		=> 'aA4',
			'b'		=> 'bB8',
			'e'		=> 'eE3',
			'i'		=> 'iI1!',
			'l'		=> 'lL1',
			'o'		=> 'oO0',
			's'		=> 'sS$',
			't'		=> 'tT7',
			'0'		=> 'oO0',
			'1'		=> 'iIlL1!',
			'3'		=> 'eE3',
			'4'		=> 'aA4',
			'7'		=> 'tT7',
			'8'		=> 'bB8',
			'$'		=> 'sS$',
			' '		=> '([\s\-_+]+)?',
			'.'		=> '\.',
			']'		=> '\]',
			'*'		=> '\*',
		);

		$len = utf8_strlen($text);
		$new_text = '';
		for ($i = 0; $i < $len; $i++)
		{
			$char = utf8_strtolower(utf8_substr($text, $i, 1));

			if (!isset($regex_ary[$char]))
			{
				$new_text .= '([' . $char . (($char != utf8_strtoupper($char)) ? utf8_strtoupper($char) : '') . ']+)';
			}
			else
			{
				$new_text .= (strpos($regex_ary[$char], '([') === false) ? '([' . $regex_ary[$char] . ']+)' : $regex_ary[$char];
			}
		}

		$endings = array('#', '%', '!', '@', '$', '%', '^', '&', '/', '|');

		foreach ($endings as $ending)
		{
			if (!strpos($new_text, $ending))
			{
				return $ending . $new_text . $ending;
				break;
			}
		}
	}

	public function reset()
	{
		$this->messages = array();
		$this->spam = false;
	}
}
?>
