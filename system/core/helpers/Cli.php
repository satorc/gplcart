<?php

/**
 * @package GPL Cart core
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2015, Iurii Makukh
 * @license https://www.gnu.org/licenses/gpl.html GNU/GPLv3
 */

namespace gplcart\core\helpers;

/**
 * Command line utilities
 */
class Cli
{

    /**
     * Parses command line arguments
     * @param array|string $argv
     * @return array
     */
    public function parse($argv)
    {
        if (is_string($argv)) {
            $argv = explode(' ', preg_replace('/\s+/', ' ', trim($argv)));
        }

        array_shift($argv);

        $out = array();
        for ($i = 0, $j = count($argv); $i < $j; $i++) {

            $key = null;
            $arg = $argv[$i];
            if (substr($arg, 0, 2) === '--') {
                $pos = strpos($arg, '=');
                if ($pos === false) {
                    $key = substr($arg, 2);
                    if ($i + 1 < $j && $argv[$i + 1][0] !== '-') {
                        $value = $argv[$i + 1];
                        $i++;
                    } else {
                        $value = isset($out[$key]) ? $out[$key] : true;
                    }

                    $out[$key] = $value;
                    continue;
                }

                $key = substr($arg, 2, $pos - 2);
                $value = substr($arg, $pos + 1);
                $out[$key] = $value;
                continue;
            }

            if (substr($arg, 0, 1) === '-') {
                if (substr($arg, 2, 1) === '=') {
                    $key = substr($arg, 1, 1);
                    $value = substr($arg, 3);
                    $out[$key] = $value;
                    continue;
                }
                $chars = str_split(substr($arg, 1));
                foreach ($chars as $char) {
                    $key = $char;
                    $value = isset($out[$key]) ? $out[$key] : true;
                    $out[$key] = $value;
                }
                if ($i + 1 < $j && $argv[$i + 1][0] !== '-') {
                    $out[$key] = $argv[$i + 1];
                    $i++;
                }
                continue;
            }

            $value = $arg;
            $out[] = $value;
        }

        return $out;
    }

    /**
     * Output a message
     * @param string $text
     * @return $this
     */
    public function out($text)
    {
        fwrite(STDOUT, $text);
        return $this;
    }

    /**
     * Output a single line with prepended new line
     * @param string $text
     * @return $this
     */
    public function line($text = '')
    {
        $text .= "\n";
        $this->out($text);
        return $this;
    }

    /**
     * Output an error message
     * @param string $text
     * @return $this
     */
    public function error($text)
    {
        fwrite(STDERR, $text);
        return $this;
    }

    /**
     * Reads a user input
     * @param string $format
     * @return string
     */
    public function in($format = '')
    {
        if (empty($format)) {
            $line = fgets(STDIN);
        } else {
            $line = fscanf(STDIN, $format . "\n", $line);
        }

        return trim($line);
    }

    /**
     * Displays an input prompt
     * @param string $question
     * @param string $default
     * @param string $marker
     * @return mixed
     */
    public function prompt($question, $default = '', $marker = ': ')
    {
        if ($default !== '' && strpos($question, '[') === false) {
            $question .= ' [' . $default . ']';
        }

        $this->out($question . $marker);

        $input = $this->in();

        if ($input === '') {
            return $default;
        }

        return $input;
    }

    /**
     * Displays a menu where a user can enter a number to choose an option
     * @param array $items An array like array('key' => 'Label')
     * @param mixed $default
     * @param string $title
     * @return mixed
     */
    public function menu($items, $default = null, $title = 'Choose an item')
    {
        if (isset($items[$default]) && strpos($title, '[') === false) {
            $title .= ' [' . $items[$default] . ']';
        }

        $this->line(sprintf('%s: ', $title));

        $i = 1;
        $keys = array();
        foreach ($items as $key => $item) {
            $keys[$i] = $key;
            $this->line(sprintf('  %d. %s', $i, $item));
            $i++;
        }

        $selected = $this->in();

        if ($selected === '') {
            return $default;
        }

        if (isset($keys[$selected])) {
            return $keys[$selected];
        }

        return $selected;
    }

    /**
     * Presents a user with a multiple choice questions
     * @param string $question
     * @param string|array $choice
     * @param string $default
     * @return string
     */
    public function choose($question, $choice = 'yn', $default = 'n')
    {
        if (!is_string($choice)) {
            $choice = implode('', $choice);
        }

        $lowercase = str_ireplace($default, strtoupper($default), strtolower($choice));
        $choices = trim(implode('/', preg_split('//', $lowercase)), '/');
        $line = $this->prompt(sprintf('%s [%s]', $question, $choices), $default, '');

        if (stripos($choice, $line) !== false) {
            return strtolower($line);
        }

        return strtolower($default);
    }

    /**
     * Abort the current script execution
     * @param integer $code
     */
    public function abort($code = 0)
    {
        exit($code);
    }

    /**
     * Executes a Shell command using exec() function
     * @param string $command
     * @param boolean $message
     * @param boolean $output
     * @return integer
     */
    public function exec($command, $message = true, $output = true)
    {
        $shell_output = array();
        exec($command . ' 2>&1', $shell_output, $result);

        if (empty($result)) {
            if ($message) {
                $this->out('OK');
            }
        } else {
            if ($message) {
                $this->error('Error');
            }
        }

        if ($output) {
            $this->out(implode("\n", $shell_output));
        }

        return $result;
    }

}
