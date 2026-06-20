<?php
/**
 * Minimal POT generator for the chinhtoa theme.
 *
 * Scans theme PHP for gettext calls with the `chinhtoa` text domain and writes
 * languages/chinhtoa.pot. A lightweight stand-in for `wp i18n make-pot` when
 * WP-CLI is unavailable. Run:
 *
 *   php tools/make-pot.php
 *
 * Handles __ _e esc_html__ esc_html_e esc_attr__ esc_attr_e _x _ex and the
 * plural forms _n _nx (singular+plural). Single- and double-quoted literals.
 */

$themeDir = __DIR__ . '/../chinhtoa';
$domain   = 'chinhtoa';
$out      = $themeDir . '/languages/' . $domain . '.pot';

$singular = '(?:__|_e|esc_html__|esc_html_e|esc_attr__|esc_attr_e|_x|_ex)';
$plural   = '(?:_n|_nx|_nx_noop|_n_noop)';

// Quoted-string sub-pattern: '...' or "..." with escaped quotes allowed.
$str = "(?:'((?:\\\\.|[^'\\\\])*)'|\"((?:\\\\.|[^\"\\\\])*)\")";

$entries = array(); // key => ['msgid'=>, 'plural'=>, 'refs'=>[], 'ctxt'=>]

$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($themeDir, FilesystemIterator::SKIP_DOTS));
foreach ($rii as $file) {
    if ($file->getExtension() !== 'php') {
        continue;
    }
    $path = $file->getPathname();
    $rel  = ltrim(str_replace($themeDir, '', $path), '/');
    $code = file_get_contents($path);
    $lines = explode("\n", $code);

    // Singular forms: fn( "msgid" , ... , 'chinhtoa' )
    $reS = '/\b' . $singular . '\s*\(\s*' . $str . '\s*(?:,\s*' . $str . '\s*)?,\s*[\'"]' . preg_quote($domain, '/') . '[\'"]\s*\)/';
    if (preg_match_all($reS, $code, $m, PREG_OFFSET_CAPTURE)) {
        foreach ($m[0] as $i => $hit) {
            $msgid = $m[1][$i][0] !== '' ? $m[1][$i][0] : $m[2][$i][0];
            if ($msgid === '' && $m[1][$i][1] < 0 && $m[2][$i][1] < 0) {
                continue;
            }
            $line = substr_count(substr($code, 0, $hit[1]), "\n") + 1;
            add_entry($entries, $msgid, null, $rel . ':' . $line);
        }
    }

    // Plural forms: _n( "single", "plural", $n, 'chinhtoa' )
    $reP = '/\b' . $plural . '\s*\(\s*' . $str . '\s*,\s*' . $str . '\s*,/';
    if (preg_match_all($reP, $code, $mp, PREG_OFFSET_CAPTURE)) {
        foreach ($mp[0] as $i => $hit) {
            // Only keep those whose call ultimately references our domain.
            $tail = substr($code, $hit[1], 300);
            if (strpos($tail, "'" . $domain . "'") === false && strpos($tail, '"' . $domain . '"') === false) {
                continue;
            }
            $sing = $mp[1][$i][0] !== '' ? $mp[1][$i][0] : $mp[2][$i][0];
            $plur = $mp[3][$i][0] !== '' ? $mp[3][$i][0] : $mp[4][$i][0];
            $line = substr_count(substr($code, 0, $hit[1]), "\n") + 1;
            add_entry($entries, $sing, $plur, $rel . ':' . $line);
        }
    }
}

function add_entry(&$entries, $msgid, $plural, $ref)
{
    if ($msgid === '' || $msgid === null) {
        return;
    }
    $key = $msgid . "\0" . (string) $plural;
    if (!isset($entries[$key])) {
        $entries[$key] = array('msgid' => $msgid, 'plural' => $plural, 'refs' => array());
    }
    $entries[$key]['refs'][$ref] = true;
}

ksort($entries);

$header = "# Copyright (C) " . date('Y') . " ToiLaTung\n"
    . "# This file is distributed under the GNU General Public License v2 or later.\n"
    . "msgid \"\"\nmsgstr \"\"\n"
    . "\"Project-Id-Version: Chinh Toa Media\\n\"\n"
    . "\"Report-Msgid-Bugs-To: https://github.com/ImTung/ChinhToa/issues\\n\"\n"
    . "\"MIME-Version: 1.0\\n\"\n"
    . "\"Content-Type: text/plain; charset=UTF-8\\n\"\n"
    . "\"Content-Transfer-Encoding: 8bit\\n\"\n"
    . "\"Language: vi\\n\"\n"
    . "\"Plural-Forms: nplurals=1; plural=0;\\n\"\n"
    . "\"X-Domain: chinhtoa\\n\"\n\n";

$body = '';
foreach ($entries as $e) {
    $refs = implode(' ', array_keys($e['refs']));
    $body .= '#: ' . $refs . "\n";
    $body .= 'msgid ' . pot_quote($e['msgid']) . "\n";
    if ($e['plural'] !== null) {
        $body .= 'msgid_plural ' . pot_quote($e['plural']) . "\n";
        $body .= "msgstr[0] \"\"\n\n";
    } else {
        $body .= "msgstr \"\"\n\n";
    }
}

function pot_quote($s)
{
    // PHP single-quote escapes -> real chars, then PO-escape.
    $s = str_replace(array("\\'", '\\\\'), array("'", '\\'), $s);
    $s = str_replace(array('\\', '"', "\n", "\t"), array('\\\\', '\\"', '\\n', '\\t'), $s);
    return '"' . $s . '"';
}

file_put_contents($out, $header . $body);
echo "Wrote " . count($entries) . " strings to " . $out . "\n";
